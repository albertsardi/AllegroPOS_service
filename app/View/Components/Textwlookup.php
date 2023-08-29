<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Textwlookup extends Component
{
    public $name;
    public $label;
    public $modal;
    public $other;
    public $other2;
    public $value;
    
    public function __construct($name, $label, $value='', $modal=null, $other=null, $other2=null)
    {
        $this->name = $name;
        $this->label = $label;
        $this->value = $value;
        $this->$modal = $modal;
        $this->$other = $other;
        $this->$other2 = $other2;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.textwselect');
    }
}
