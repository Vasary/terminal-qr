<?php

declare(strict_types = 1);

namespace App\Presentation\Console;

use App\Application\Payment\Business\PaymentFacadeInterface;
use App\Domain\ValueObject\Id;
use App\Infrastructure\Command\AbstractCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class UpdatePaymentPaymentStatusCommand extends AbstractCommand
{
    public function __construct(private readonly PaymentFacadeInterface $paymentFacade)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('payment:update')
            ->addArgument('id', InputArgument::REQUIRED, 'Payment id');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $paymentId = $input->getArgument('id');

        $this->paymentFacade->handle(Id::fromString($paymentId));

        return self::SUCCESS;
    }
}
