<?php

namespace Plugin\Security;

class SecurityPlugin
{
    protected $_context;

    protected $_name = 'Security';

    public function setContext($context)
    {

    }

    public function allow()
    {
        $c = $this->_context;

        if ($c instanceof Route) {

        }

        if ($c instanceof Module) {

        }

        if ($c instanceof Controller) {

        }

        if ($c instanceof Action) {

        }

        if ($c instanceof TableGetaway) {

        }
    }
}