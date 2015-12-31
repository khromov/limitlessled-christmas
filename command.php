<?php
include 'bootstrap.php';

echo "Starting program!" . PHP_EOL;

//Init
$lights = new ChristmasLights(MILIGHT_WIFI_BRIDGE_IP);
$groups = [1,2,3,4];

//Do the christmas dance!
while(true) {

    for($i = 0; $i <= 6; $i++) {
        $lights->set_color($lights->get_rotated_hex_color($i), 1000);
    }

    for($i = 1; $i <= 13; $i++) {
        $lights->set_color($lights->get_rotated_hex_color($i), 500);
    }

    for($i = 2; $i <= 26; $i++) {
        $lights->set_color($lights->get_rotated_hex_color($i), 0);
    }

    //Make the rotations crazy!
    for($i = 3; $i <= 67; $i++) {
        $lights->set_color($lights->get_rotated_hex_color($i), 0, $lights->get_rotated_group_number($i));
    }

    echo "Did rotation!" . PHP_EOL;
}

/**
 * Class ChristmasLights
 */
class ChristmasLights {
    private $interface;

    /**
     * ChristmasLights constructor.
     * @param $ip
     * @param int $delay
     */
    function __construct($ip, $delay = 20000) {
        $this->interface = new Milight($ip);
        $this->interface->setDelay($delay); //Slightly bump delay to avoid timing issues
    }

    /**
     * Initializes LEDs so they're ready.
     *
     * @param string $initial_color
     */
    function init_leds($initial_color = 'ff0000') {
        $this->interface->rgbwAllOn();
        $this->interface->rgbwSetColorHexString($initial_color);
        $this->interface->rgbwAllBrightnessMax();
    }

    /**
     * Sets bulb color
     *
     * @param $hex
     * @param int $wait
     * @param null $group
     */
    function set_color($hex, $wait = 500, $group = null) {

        if($group !== null) {
            $this->interface->rgbwSetActiveGroup($group);
        }

        $this->interface->rgbwSetColorHexString($hex);
        usleep($wait*1000); //In ms

        //Make sure to reset active group to "all" after our command
        if($group !== null) {
            $this->interface->rgbwSetActiveGroup(0);
        }
    }

    function get_random_hex_color() {
        return sprintf('#%06X', mt_rand(0, 0xFFFFFF));
    }

    function get_rotated_hex_color($index) {

        $colors = [
            'ff0000',
            '00ff00',
            '0000ff'
        ];

        return $colors[$index%sizeof($colors)];
    }

    function get_rotated_group_number($index) {
        $groups = [1,2,3,4];

        return $groups[$index%sizeof($groups)];
    }
}