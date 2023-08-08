<?php

declare(strict_types = 1);

namespace App\Infrastructure\Persistence\Doctrine\Strategy\Quote;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\QuoteStrategy;

final class EscapingQuoteStrategy implements QuoteStrategy
{
    /**
     * {@inheritdoc}
     */
    public function getColumnName($fieldName, ClassMetadata $class, AbstractPlatform $platform)
    {
        if (isset($class->fieldMappings[$fieldName]['quoted'])) {
            return $platform->quoteIdentifier($class->fieldMappings[$fieldName]['columnName']);
        }

        $reservedKeyList = $platform->getReservedKeywordsList();
        if ($reservedKeyList->isKeyword($fieldName)) {
            return $platform->quoteIdentifier($class->fieldMappings[$fieldName]['columnName']);
        }

        return $class->fieldMappings[$fieldName]['columnName'];
    }

    /**
     * {@inheritdoc}
     */
    public function getTableName(ClassMetadata $class, AbstractPlatform $platform)
    {
        if (isset($class->table['quoted'])) {
            return $platform->quoteIdentifier($class->table['name']);
        }
        $reservedKeyList = $platform->getReservedKeywordsList();
        if ($reservedKeyList->isKeyword($class->table['name'])) {
            return $platform->quoteIdentifier($class->table['name']);
        }

        return $class->table['name'];
    }

    /**
     * {@inheritdoc}
     */
    public function getSequenceName(array $definition, ClassMetadata $class, AbstractPlatform $platform)
    {
        if (isset($definition['quoted'])) {
            return $platform->quoteIdentifier($class->table['name']);
        }
        $reservedKeyList = $platform->getReservedKeywordsList();
        if ($reservedKeyList->isKeyword($definition['sequenceName'])) {
            return $platform->quoteIdentifier($definition['sequenceName']);
        }

        return $definition['sequenceName'];
    }

    /**
     * {@inheritdoc}
     */
    public function getJoinColumnName(array $joinColumn, ClassMetadata $class, AbstractPlatform $platform)
    {
        if (isset($joinColumn['quoted'])) {
            return $platform->quoteIdentifier($joinColumn['name']);
        }
        $reservedKeyList = $platform->getReservedKeywordsList();
        if ($reservedKeyList->isKeyword($joinColumn['name'])) {
            return $platform->quoteIdentifier($joinColumn['name']);
        }

        return $joinColumn['name'];
    }

    /**
     * {@inheritdoc}
     */
    public function getReferencedJoinColumnName(array $joinColumn, ClassMetadata $class, AbstractPlatform $platform)
    {
        if (isset($joinColumn['quoted'])) {
            return $platform->quoteIdentifier($joinColumn['referencedColumnName']);
        }
        $reservedKeyList = $platform->getReservedKeywordsList();
        if ($reservedKeyList->isKeyword($joinColumn['referencedColumnName'])) {
            return $platform->quoteIdentifier($joinColumn['referencedColumnName']);
        }

        return $joinColumn['referencedColumnName'];
    }

    /**
     * {@inheritdoc}
     */
    public function getJoinTableName(array $association, ClassMetadata $class, AbstractPlatform $platform)
    {
        if (isset($association['joinTable']['quoted'])) {
            return $platform->quoteIdentifier($association['joinTable']['name']);
        }
        $reservedKeyList = $platform->getReservedKeywordsList();
        if ($reservedKeyList->isKeyword($association['joinTable']['name'])) {
            return $platform->quoteIdentifier($association['joinTable']['name']);
        }

        return $association['joinTable']['name'];
    }

    /**
     * {@inheritdoc}
     */
    public function getIdentifierColumnNames(ClassMetadata $class, AbstractPlatform $platform)
    {
        $quotedColumnNames = array();

        foreach ($class->identifier as $fieldName) {
            if (isset($class->fieldMappings[$fieldName])) {
                $quotedColumnNames[] = $this->getColumnName($fieldName, $class, $platform);

                continue;
            }

            // Association defined as Id field
            $joinColumns = $class->associationMappings[$fieldName]['joinColumns'];
            $assocQuotedColumnNames = array_map(
                function ($joinColumn) use ($platform) {
                    if (isset($joinColumn['quoted'])) {
                        return $platform->quoteIdentifier($joinColumn['name']);
                    }
                    $reservedKeyList = $platform->getReservedKeywordsList();
                    if ($reservedKeyList->isKeyword($joinColumn['name'])) {
                        return $platform->quoteIdentifier($joinColumn['name']);
                    }

                    return $joinColumn['name'];
                },
                $joinColumns
            );

            $quotedColumnNames = array_merge($quotedColumnNames, $assocQuotedColumnNames);
        }

        return $quotedColumnNames;
    }

    /**
     * {@inheritdoc}
     */
    public function getColumnAlias($columnName, $counter, AbstractPlatform $platform, ClassMetadata $class = null)
    {
        // 1 ) Concatenate column name and counter
        // 2 ) Trim the column alias to the maximum identifier length of the platform.
        //     If the alias is to long, characters are cut off from the beginning.
        // 3 ) Strip non alphanumeric characters
        // 4 ) Prefix with "_" if the result its numeric
        $columnName = $columnName.'_'.$counter;
        $columnName = substr($columnName, -$platform->getMaxIdentifierLength());
        $columnName = preg_replace('/[^A-Za-z0-9_]/', '', $columnName);
        $columnName = is_numeric($columnName)
            ? '_'.$columnName
            : $columnName;

        return $platform->getSQLResultCasing($columnName);
    }
}
