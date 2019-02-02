<?php namespace ss\components\products\controllers;

class Main extends \Controller
{
    public function reload()
    {
        $this->jquery('|')->replace($this->view());
    }

    public function view()
    {
        $v = $this->v('|');

        $cat = $this->data('cat');

        $v->assign([
                       'CONTENT' => $this->c('\ss\products\cp~:view', [
                           'cat' => $cat
                       ])
                   ]);

        $this->css();

        return $v;
    }
}
