<?php namespace ss\components\products\ui\controllers\product;

class Props extends \Controller
{
    public function view()
    {
        $v = $this->v();

        $props = $this->data('props');

        $tableOpened = false;

        foreach ((array)$props as $n => $prop) {
            $label = $prop['label'];
            $value = $prop['value'];

            if ($label) {
                if (!$tableOpened) {
                    $v->assign('part');
                    $v->assign('part/table');

                    $tableOpened = true;
                }

                $v->assign('part/table/row', [
                    'ODD_CLASS' => $n % 2 == 0 ? 'odd' : '',
                    'LABEL'     => $label,
                    'VALUE'     => $value
                ]);
            } else {
                if ($tableOpened) {
                    $tableOpened = false;
                }

                $v->assign('part');
                $v->assign('part/text', [
                    'VALUE' => $value
                ]);
            }
        }

        $this->css();

        $this->c('\plugins\perfectScrollbar~:bind', [ ////
                                                      'selector' => $this->_selector()
        ]);

        return $v;
    }
}
