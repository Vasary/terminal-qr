<?php

declare(strict_types = 1);

namespace App\Infrastructure\Twig;

use App\Domain\Model\Payment;
use Symfony\Component\Workflow\Dumper\MermaidDumper;
use Symfony\Component\Workflow\WorkflowInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class WorkflowExtension extends AbstractExtension
{
    public function __construct(private readonly WorkflowInterface $paymentStateMachine)
    {
    }

    /**
     * @return TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('paymentWorkflow', [$this, 'createWorkflow']),
        ];
    }

    public function createWorkflow(Payment $payment): string
    {
        $dumper = new MermaidDumper(
            MermaidDumper::TRANSITION_TYPE_STATEMACHINE,
            MermaidDumper::DIRECTION_LEFT_TO_RIGHT
        );

        return $dumper->dump($this->paymentStateMachine->getDefinition(), $this->paymentStateMachine->getMarking($payment));
    }
}
