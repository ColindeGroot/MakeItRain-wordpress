<?php
 namespace MailPoetVendor\Twig\Node; if (!defined('ABSPATH')) exit; use MailPoetVendor\Twig\Compiler; use MailPoetVendor\Twig\Node\Expression\AbstractExpression; class DoNode extends \MailPoetVendor\Twig\Node\Node { public function __construct(\MailPoetVendor\Twig\Node\Expression\AbstractExpression $expr, int $lineno, string $tag = null) { parent::__construct(['expr' => $expr], [], $lineno, $tag); } public function compile(\MailPoetVendor\Twig\Compiler $compiler) { $compiler->addDebugInfo($this)->write('')->subcompile($this->getNode('expr'))->raw(";\n"); } } \class_alias('MailPoetVendor\\Twig\\Node\\DoNode', 'MailPoetVendor\\Twig_Node_Do'); 