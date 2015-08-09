<?php

class DebugWindow {

    private $start;
    private $db;

    public function __construct(\Snabb\Database\Connection $db, $start) {
        $this->start = $start;
        $this->db = $db;
    }

    private function toMiliseconds($seconds) {
        return round($seconds * 1000, 3);
    }

    public function __destruct() {
        $sql_generating = $generating = microtime(true) - $this->start;
        $sql_table = "<style type='text/css'>table td, table th { border: 1px #000000 solid; padding: 5px } table {border-collapse: collapse; width: 100%;}</style>";
        $sql_table .= "<div><table><caption>SQL dotazy</caption>";
        $sql_table .= "<tr><th>Pořadí</th><th>Typ</th><th>SQL dotaz</th><th>Čas vyřízení v ms</th><th>Status</th></tr>";

        $queue = 1;
        foreach ($this->db->getExecutedQueries() as $sql => $row) {
            $sql_table .= "<tr><td>" . ($queue++) . "</td><td>" . e_html($row['type']) . "</td><td><code>" . e_html($sql) . "</code></td><td>" . e_html($this->toMiliseconds($row['duration'])) . "</td><td>" . $row['status'] . "</td></tr>";
            $sql_generating -= $row['duration'];
        }

        $sql_table .= "</table></div><br />";

        if ($errors = class_exists('ErrorHandler', false)) {
//      $sql_table .= "<div><table><caption>Zachycené chyby při generování HTML</caption>";
//      $sql_table .= "<tr><th>Pořadí</th><th>Typ</th><th>text chyby</th><th>soubor</th><th>řádka</th></tr>";
//      foreach(ErrorHandler::$catched_errors as $key => $row)
//        $sql_table .= "<tr><td>".($key+1)."</td><td>".e_html($row['type'])."</td><td>".e_html($row['text'])."</td><td>".e_html($row['file'])."</td><td>".e_html($row['line'])."</td></tr>";
//      $sql_table .= "</table></div><br />";
        }

        $sql_table .= "<code>Doba vyřizování všech SQL dotazů: " . $this->toMiliseconds($generating - $sql_generating) . " ms<br />";
        $sql_table .= "Doba generování stránky: " . $this->toMiliseconds($generating) . " ms<br />";
        $sql_table .= "Použitá paměť: " . round((memory_get_peak_usage(true) / 1024 / 1024), 2) . " MB<br />";
        $sql_table .= "Operační systém: " . php_uname() . "<br />";
        $sql_table .= "Verze php na serveru je: " . PHP_VERSION . "<br />";
        $sql_table .= "Databázový driver: " . $this->db->getDriverName() . "<br />";
        $sql_table .= "Web server: " . $_SERVER['SERVER_SOFTWARE'] . "<br />";
        $sql_table .= "Načtené soubory: ";
        $sql_table .= "<ul style='margin:0; padding-top:0; padding-left:15px;'>";
        foreach (get_included_files() as $value)
            $sql_table .= "<li>" . str_replace(getcwd(), ".", $value) . "</li>";
        $sql_table .= "</ul></code>";
        echo "<script type='text/javascript' src='" . _PATH_ . "js/popupWindow.js'></script>";
        echo '<a id="debug_link" href="javascript: void(0);" ', ($errors ? 'style="background-color: #EE2244; color: black;"' : ''), 'onclick="popupWindow(\'' . htmlentities(addslashes($sql_table), ENT_QUOTES, "UTF-8") . '\', this); return this.parentNode.removeChild(this);">Debug okno</a>';
    }
}