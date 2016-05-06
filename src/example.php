<?php

namespace Example;

use Carnage\Selenium\Collection\Step;
use Carnage\Selenium\Collection\TestSuite;
use Carnage\Selenium\Collection\UseCase;
use Carnage\Selenium\Primitive\Action;
use Carnage\Selenium\Primitive\Assert;
use Carnage\Selenium\Primitive\Element;
use Carnage\Selenium\ValueObject\Uri;

return TestSuite::fromUseCases(
    UseCase::fromSteps(
        Uri::fromString('/login'),
        Step::fromPrimitives(
            Action::type('my-username', Element::byId('username')),
            Action::type('my-password', Element::byId('password')),
            Action::click(Element::byCssSelector('.submit-button')),

            Assert::uriEquals(Uri::fromString('/login-success-page')),
            Assert::pageTitleEquals('Example.com logged in page'),
            Assert::elementContains(Element::byId('welcome-message'), 'Welcome back my-username')
        ),

        Step::fromPrimitives(
            Action::click(Element::byId('logout-button')),

            Assert::uriEquals(Uri::fromString('/logged-out')),
            Assert::pageTitleEquals('Example.com'),
            Assert::elementContains(Element::byId('message'), 'Logout successful. Come back soon!')
        )
    ),
    UseCase::fromSteps(
        Uri::fromString('/login'),
        Step::fromPrimitives(
            Action::type('my-username-2', Element::byId('username')),
            Action::type('my-password-2', Element::byId('password')),
            Action::click(Element::byCssSelector('.submit-button')),

            Assert::uriEquals(Uri::fromString('/login-success-page')),
            Assert::pageTitleEquals('Example.com logged in page'),
            Assert::elementContains(Element::byId('welcome-message'), 'Welcome back my-username-2')
        ),

        Step::fromPrimitives(
            Action::click(Element::byId('logout-button')),

            Assert::uriEquals(Uri::fromString('/logged-out')),
            Assert::pageTitleEquals('Example.com'),
            Assert::elementContains(Element::byId('message'), 'Logout successful. Come back soon!')
        )
    )
);