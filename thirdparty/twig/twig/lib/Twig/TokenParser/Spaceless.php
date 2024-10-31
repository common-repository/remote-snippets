<?php

namespace RS_Vendor;

use RS_Vendor\Twig\TokenParser\SpacelessTokenParser;
\class_exists('RS_Vendor\\Twig\\TokenParser\\SpacelessTokenParser');
@\trigger_error(\sprintf('Using the "Twig_TokenParser_Spaceless" class is deprecated since Twig version 2.7, use "Twig\\TokenParser\\SpacelessTokenParser" instead.'), \E_USER_DEPRECATED);
if (\false) {
    /** @deprecated since Twig 2.7, use "Twig\TokenParser\SpacelessTokenParser" instead */
    class Twig_TokenParser_Spaceless extends \RS_Vendor\Twig\TokenParser\SpacelessTokenParser
    {
    }
}
