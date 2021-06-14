<?php
 namespace MailPoetVendor\Symfony\Component\Validator\Constraints; if (!defined('ABSPATH')) exit; use MailPoetVendor\Egulias\EmailValidator\Validation\EmailValidation; use MailPoetVendor\Egulias\EmailValidator\Validation\NoRFCWarningsValidation; use MailPoetVendor\Symfony\Component\Validator\Constraint; use MailPoetVendor\Symfony\Component\Validator\ConstraintValidator; use MailPoetVendor\Symfony\Component\Validator\Exception\LogicException; use MailPoetVendor\Symfony\Component\Validator\Exception\UnexpectedTypeException; use MailPoetVendor\Symfony\Component\Validator\Exception\UnexpectedValueException; class EmailValidator extends \MailPoetVendor\Symfony\Component\Validator\ConstraintValidator { const PATTERN_HTML5 = '/^[a-zA-Z0-9.!#$%&\'*+\\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)+$/'; const PATTERN_LOOSE = '/^.+\\@\\S+\\.\\S+$/'; private static $emailPatterns = [\MailPoetVendor\Symfony\Component\Validator\Constraints\Email::VALIDATION_MODE_LOOSE => self::PATTERN_LOOSE, \MailPoetVendor\Symfony\Component\Validator\Constraints\Email::VALIDATION_MODE_HTML5 => self::PATTERN_HTML5]; private $defaultMode; public function __construct($defaultMode = \MailPoetVendor\Symfony\Component\Validator\Constraints\Email::VALIDATION_MODE_LOOSE) { if (\is_bool($defaultMode)) { @\trigger_error(\sprintf('Calling `new %s(%s)` is deprecated since Symfony 4.1, use `new %s("%s")` instead.', self::class, $defaultMode ? 'true' : 'false', self::class, $defaultMode ? \MailPoetVendor\Symfony\Component\Validator\Constraints\Email::VALIDATION_MODE_STRICT : \MailPoetVendor\Symfony\Component\Validator\Constraints\Email::VALIDATION_MODE_LOOSE), \E_USER_DEPRECATED); $defaultMode = $defaultMode ? \MailPoetVendor\Symfony\Component\Validator\Constraints\Email::VALIDATION_MODE_STRICT : \MailPoetVendor\Symfony\Component\Validator\Constraints\Email::VALIDATION_MODE_LOOSE; } if (!\in_array($defaultMode, \MailPoetVendor\Symfony\Component\Validator\Constraints\Email::$validationModes, \true)) { throw new \InvalidArgumentException('The "defaultMode" parameter value is not valid.'); } $this->defaultMode = $defaultMode; } public function validate($value, \MailPoetVendor\Symfony\Component\Validator\Constraint $constraint) { if (!$constraint instanceof \MailPoetVendor\Symfony\Component\Validator\Constraints\Email) { throw new \MailPoetVendor\Symfony\Component\Validator\Exception\UnexpectedTypeException($constraint, \MailPoetVendor\Symfony\Component\Validator\Constraints\Email::class); } if (null === $value || '' === $value) { return; } if (!\is_scalar($value) && !(\is_object($value) && \method_exists($value, '__toString'))) { throw new \MailPoetVendor\Symfony\Component\Validator\Exception\UnexpectedValueException($value, 'string'); } $value = (string) $value; if ('' === $value) { return; } if (null !== $constraint->normalizer) { $value = ($constraint->normalizer)($value); } if (null !== $constraint->strict) { @\trigger_error(\sprintf('The %s::$strict property is deprecated since Symfony 4.1. Use %s::mode="%s" instead.', \MailPoetVendor\Symfony\Component\Validator\Constraints\Email::class, \MailPoetVendor\Symfony\Component\Validator\Constraints\Email::class, \MailPoetVendor\Symfony\Component\Validator\Constraints\Email::VALIDATION_MODE_STRICT), \E_USER_DEPRECATED); if ($constraint->strict) { $constraint->mode = \MailPoetVendor\Symfony\Component\Validator\Constraints\Email::VALIDATION_MODE_STRICT; } else { $constraint->mode = \MailPoetVendor\Symfony\Component\Validator\Constraints\Email::VALIDATION_MODE_LOOSE; } } if (null === $constraint->mode) { $constraint->mode = $this->defaultMode; } if (!\in_array($constraint->mode, \MailPoetVendor\Symfony\Component\Validator\Constraints\Email::$validationModes, \true)) { throw new \InvalidArgumentException(\sprintf('The "%s::$mode" parameter value is not valid.', \get_class($constraint))); } if (\MailPoetVendor\Symfony\Component\Validator\Constraints\Email::VALIDATION_MODE_STRICT === $constraint->mode) { if (!\class_exists('MailPoetVendor\\Egulias\\EmailValidator\\EmailValidator')) { throw new \MailPoetVendor\Symfony\Component\Validator\Exception\LogicException('Strict email validation requires egulias/email-validator ~1.2|~2.0.'); } $strictValidator = new \MailPoetVendor\Egulias\EmailValidator\EmailValidator(); if (\interface_exists(\MailPoetVendor\Egulias\EmailValidator\Validation\EmailValidation::class) && !$strictValidator->isValid($value, new \MailPoetVendor\Egulias\EmailValidator\Validation\NoRFCWarningsValidation())) { $this->context->buildViolation($constraint->message)->setParameter('{{ value }}', $this->formatValue($value))->setCode(\MailPoetVendor\Symfony\Component\Validator\Constraints\Email::INVALID_FORMAT_ERROR)->addViolation(); return; } elseif (!\interface_exists(\MailPoetVendor\Egulias\EmailValidator\Validation\EmailValidation::class) && !$strictValidator->isValid($value, \false, \true)) { $this->context->buildViolation($constraint->message)->setParameter('{{ value }}', $this->formatValue($value))->setCode(\MailPoetVendor\Symfony\Component\Validator\Constraints\Email::INVALID_FORMAT_ERROR)->addViolation(); return; } } elseif (!\preg_match(self::$emailPatterns[$constraint->mode], $value)) { $this->context->buildViolation($constraint->message)->setParameter('{{ value }}', $this->formatValue($value))->setCode(\MailPoetVendor\Symfony\Component\Validator\Constraints\Email::INVALID_FORMAT_ERROR)->addViolation(); return; } $host = (string) \substr($value, \strrpos($value, '@') + 1); if ($constraint->checkMX) { if (!$this->checkMX($host)) { $this->context->buildViolation($constraint->message)->setParameter('{{ value }}', $this->formatValue($value))->setCode(\MailPoetVendor\Symfony\Component\Validator\Constraints\Email::MX_CHECK_FAILED_ERROR)->addViolation(); } return; } if ($constraint->checkHost && !$this->checkHost($host)) { $this->context->buildViolation($constraint->message)->setParameter('{{ value }}', $this->formatValue($value))->setCode(\MailPoetVendor\Symfony\Component\Validator\Constraints\Email::HOST_CHECK_FAILED_ERROR)->addViolation(); } } private function checkMX(string $host) : bool { return '' !== $host && \checkdnsrr($host, 'MX'); } private function checkHost(string $host) : bool { return '' !== $host && ($this->checkMX($host) || (\checkdnsrr($host, 'A') || \checkdnsrr($host, 'AAAA'))); } } 