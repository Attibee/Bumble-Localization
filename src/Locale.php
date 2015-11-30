<?php

/* Copyright 2015 Attibee (http://attibee.com)
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 * 
 *     http://www.apache.org/licenses/LICENSE-2.0
 * 
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace Bumble\Locale;

/**
 * A interface to access Unicode's localization data.
 */
class Locale {
    private $dom = null;
    private $xpath = null;
    private $parentLocale = null;
    private $identifier = "";
    private $getter = array();
    
    /**
     * Sets the identifier of the Locale.
     * 
     * Sets the identifier o the Locale. This does not load the Locale. Use
     * {@link LocaleFactory} instead.
     * 
     * @param string $identifier The Locale's identifier.
     */
    public function __construct( $identifier ) {
        $this->identifier = $identifier;
        $this->getter = array(
            'symbols' =>'\Bumble\Locale\Getter\SymbolGetter',
            'language' => '\Bumble\Locale\Getter\LanguageGetter',
            'variant' => '\Bumble\Locale\Getter\VariantGetter',
            'territory' => '\Bumble\Locale\Getter\TerritoryGetter',
            'script' => '\Bumble\Locale\Getter\ScriptGetter',
            'defaultNumberingSystem' => '\Bumble\Locale\Getter\DefaultNumberingSystemGetter',
            'decimalFormat' => '\Bumble\Locale\Getter\DecimalFormatGetter',
            'currencyFormat' => '\Bumble\Locale\Getter\CurrencyFormatGetter',
            'percentFormat' => '\Bumble\Locale\Getter\PercentFormatGetter',
            'languages' => '\Bumble\Locale\Getter\LanguagesGetter'
        );
    }
    
    public function __get( $name ) {
        if( isset( $this->getter[$name] ) ) {
            //not yet created, let's create the object
            if( is_string( $this->getter[$name] ) ) {
                $this->getter[$name] = new $this->getter[$name]($this);
            }
            
            return $this->getter[$name];
        }
        
        throw new Exception\InvalidGetterException("$name is not a valid Locale getter.");
    }
    
    public function __call( $name, $arguments ) {
        if( isset( $this->getter[$name] ) ) {
            //not yet created, let's create the object
            if( is_string( $this->getter[$name] ) ) {
                $this->getter[$name] = new $this->getter[$name]($this);
            }
            
            return $this->getter[$name]($arguments);
        }

        throw new Exception\InvalidGetterException("$name is not a valid Locale getter.");
    }
    
    public function setDom( \DomDocument $dom ) {
        $this->dom = $dom;
    }
    
    public function setParentLocale( Locale $parent ) {
        $this->parentLocale = $parent;
    }
    
    private function getXpath() {
        if( !$this->xpath )
            $this->xpath = new \DOMXpath( $this->dom );
        
        return $this->xpath;
    }
    
    public function queryCollection( $query ) {
        $nodes = $this->getXpath()->query( $query );
        
        if( $nodes->length === 0 ) {
            if( $this->parentLocale === null ) //end recursion, no parents
                $nodes = null;
            else
                $nodes = $this->parentLocale->queryCollection( $query );
        }
        
        return $nodes;
    }
    
    public function query( $query ) {
        return $this->getXpath()->query( $query );
    }

}