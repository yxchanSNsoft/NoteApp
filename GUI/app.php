<?php


class App {

    private $dir;
    private $category;

    function __construct($dir) {
        $this->dir = $dir;
        $this->category = array();
    }

    private function get_category() {
        $cdir = scandir ($this->dir);
        for ( $i = 0 ; $i < COUNT($cdir); $i++ ) {
          if ($cdir[$i] != "." AND $cdir[$i] != ".." AND !is_file($this->dir . "/" . $cdir[$i])) {
            $this->category[] = $cdir[$i];
          }
        }
    }

    public function render_menu() {
        $this->get_category();
        $categories = $this->category;
        $menuHTML = "<div id='myUL' class=''><ul>";
        if (COUNT($categories) > 0) {
            for ($i=0;$i<COUNT($categories);$i++) { // loop through categories
                $menuHTML .= "<li><span class='caret' name='menu_category'>{$categories[$i]}</span>";
                $notes = $this->get_notes($categories[$i]);
                if (COUNT($notes) > 0) {
                    $menuHTML .= "<ul class='nested'>";
                    for ($o=0;$o<COUNT($notes);$o++) { // loop through notes
                        $menuHTML .= "<li style='overflow:auto'><i style='font-size:24px' class='fas'>&#xf187;</i><span class='menu_note' name='menu_note'>{$notes[$o]}</span></li>";
                    }
                    $menuHTML .= "</ul>";
                }
            }
        }
        $menuHTML .= "</ul></div>";
        
        return $menuHTML;
    }

    private function get_notes($category) {
        $cnote = scandir ($this->dir . "/" . $category);
        unset($cnote[0]);
        unset($cnote[1]);
        return array_values($cnote);
    }

    public function get_note($note, $category, $relativePath) {
        //$cnote = scandir ($this->dir . "/" . $category . "/" . $note);
        $cnote = scandir ( $relativePath . $category . "/" . $note);
        unset($cnote[0]);
        unset($cnote[1]);
        return array_values($cnote);
    }

    public function save($fileName, $fileContent, $dir, $relativePath) {

      // check if directory exists, if it doesnt, create it.
      if (!file_exists($relativePath . $dir)) {
        $result = mkdir($relativePath . $dir);
      }
      
      
        $x = file_put_contents( $relativePath . $dir . "/index.html", $fileContent, LOCK_EX );
        if ($x) {
            return true;
        } else {
            return false;
        }
    }


}

/*
<ul id="myUL">
  <li><span class="caret">Beverages</span>
    <ul class="nested">
      <li>Water</li>
      <li>Coffee</li>
      <li><span class="caret">Tea</span>
        <ul class="nested">
          <li>Black Tea</li>
          <li>White Tea</li>
          <li><span class="caret">Green Tea</span>
            <ul class="nested">
              <li>Sencha</li>
              <li>Gyokuro</li>
              <li>Matcha</li>
              <li>Pi Lo Chun</li>
            </ul>
          </li>
        </ul>
      </li>
    </ul>
  </li>
</ul>
*/