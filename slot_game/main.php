<?php
$json = file_get_contents('config.json');
$json_data = json_decode($json, TRUE);

$reels = $json_data['reels'][0];
$lines = $json_data['lines'];
$pays = $json_data['pays'];

$winning_lines = array();
$winning_numbers = array();
$payment = 0;
shuffle($reels);

#getting current spin symbols
for ($i = 0; $i < 3; $i++) {
    shuffle($reels);
    for ($k = 0; $k < 5; $k++) {
        shuffle($reels);
        $current_spin[$i][$k] = $reels[$k][$i];
    }
}

$mystery_symbol = rand(1, 9);
$symbol_counter = 0;

print_r("Current spin symbols:\n");
for ($i = 0; $i < 3; $i++) {
    for ($k = 0; $k < 5; $k++) {
        if ($current_spin[$i][$k] == 10) {
            $current_spin[$i][$k] = $mystery_symbol;
            $symbol_counter++;
        }
        print_r($current_spin[$i][$k]." ");
    }
    print_r("\n");
}

if ($symbol_counter == 0) {
    $mystery_symbol = 0;
}

#main logic to check if the current spin has winning lines
for ($i = 0; $i < 10; $i++) {
    $counter = 1;
    for ($k = 0; $k < sizeof($lines[0]) - 1; $k++) {
        $var = $current_spin[$lines[$i][$k]][$k];

        if ($var == $var_new = $current_spin[$lines[$i][$k + 1]][$k + 1]) {
            $counter++;
        } else {
            $counter = 1;
        }
        if ($counter >= 3) {
            $winning_lines[] = $lines[$i];
            $winning_numbers[$counter][] = $var;
            if ($counter == 4) {

              if($key = in_array($var, $winning_numbers[3],true)){
                   array_pop($winning_numbers[3]);
               }
            }
            if ($counter == 5) {
                if($key = in_array($var, $winning_numbers[4], true)){
                    array_pop($winning_numbers[4]);
                }
            }
        }
    }
}
#printing al necessary information
if (sizeof($winning_lines) != 0) {
    #adjusting the payment
    foreach ($winning_numbers as $reps => $winning_number) {
        foreach ($winning_number as $k => $value) {
            for ($i = 0; $i < sizeof($pays); $i++) {
                if ($value == $pays[$i][0] && $reps == $pays[$i][1]) {
                    $payment += 0.1 * $pays[$i][2];
                }
            }
        }
    }
    print_r("You have won: " . $payment."$! Congratulations!\n");

    #printing the winning lines
    $winning_lines = array_unique($winning_lines, SORT_REGULAR);
    echo("Winning lines: " . sizeof($winning_lines) . "\n");
    $keys = array_keys($winning_lines);
    for($i = 0; $i < sizeof($winning_lines); $i++){
        foreach($winning_lines[$keys[$i]] as $key => $number){
            print_r($number. " ");
        }
        print_r("\n");
    }

} else {
    echo("There are no winning lines in the current spin!\n");
}
if ($mystery_symbol != 0) {
    echo("The mystery symbol [10] has been transformed into: " . $mystery_symbol);
} else {
    echo("There was no mystery symbol in the current spin!");
}