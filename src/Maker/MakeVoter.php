<?php

/*
 * This file is part of the Symfony MakerBundle package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Bundle\MakerBundle\Maker;

use Symfony\Bundle\MakerBundle\ConsoleStyle;
use Symfony\Bundle\MakerBundle\DependencyBuilder;
use Symfony\Bundle\MakerBundle\Generator;
use Symfony\Bundle\MakerBundle\InputConfiguration;
use Symfony\Bundle\MakerBundle\Str;
use Symfony\Bundle\MakerBundle\Util\ClassNameDetails;
use Symfony\Bundle\MakerBundle\Validator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * @author Javier Eguiluz <javier.eguiluz@gmail.com>
 * @author Ryan Weaver <weaverryan@gmail.com>
 */
final class MakeVoter extends AbstractMaker
{
    public static function getCommandName(): string
    {
        return 'make:voter';
    }

    public function configureCommand(Command $command, InputConfiguration $inputConf)
    {
        $command
            ->setDescription('Creates a new security voter class')
            ->addArgument('name', InputArgument::OPTIONAL, 'The name of the security voter class (e.g. <fg=yellow>BlogPostVoter</>).')
            ->setHelp(file_get_contents(__DIR__.'/../Resources/help/MakeVoter.txt'))
        ;
    }

    public function generate(InputInterface $input, ConsoleStyle $io, Generator $generator)
    {
        $voterClassNameDetails = ClassNameDetails::createFromName(
            $input->getArgument('name'),
            'App\\Security\\Voter\\',
            'Voter'
        );

        $generator->generateClass(
            $voterClassNameDetails->getFullName(),
            'security/Voter.tpl.php',
            []
        );

        $this->writeSuccessMessage($io);

        $io->text([
            'Next: Open your voter and add your logic.',
            'Find the documentation at <fg=yellow>https://symfony.com/doc/current/security/voters.html</>',
        ]);
    }

    public function configureDependencies(DependencyBuilder $dependencies)
    {
        $dependencies->addClassDependency(
            Voter::class,
            'security'
        );
    }
}
