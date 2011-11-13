<?php
echo '<table style="margin: 20px;">';
echo '<colgroup><col width="70" /><col width="400" /></colgroup>';
echo '<tr>';
echo '<th style="font-weight: bold; text-align: left;">Fragment:</th><td>Standard 2</td>';
echo '</tr><tr>';
echo '<th style="font-weight: bold; text-align: left;">Before:</th><td>'.$this->before.'</td>';
echo '</tr><tr>';
echo '<th style="font-weight: bold; text-align: left;">Field:</th><td>'.$this->field.'</td>';
echo '</tr><tr>';
echo '<th style="font-weight: bold; text-align: left;">Label:</th><td>'.$this->label.'</td>';
echo '</tr><tr>';
echo '<th style="font-weight: bold; text-align: left;">After:</th><td>'.$this->after.'</td>';
echo '</tr><tr>';
echo '<th style="font-weight: bold; text-align: left;">Name:</th><td>'.$this->name.'</td>';
echo '</tr><tr>';
echo '<th style="font-weight: bold; text-align: left;">Class:</th><td>'.$this->class.'</td>';
echo '</tr><tr>';
echo '<th style="font-weight: bold; text-align: left;">Html Id:</th><td>'.$this->html_id.'</td>';
echo '</tr>';
echo '</table>';
?>