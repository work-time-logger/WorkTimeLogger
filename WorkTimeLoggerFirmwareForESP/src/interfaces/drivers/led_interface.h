#ifndef LED_INTERFACE_H
#define LED_INTERFACE_H

typedef void (*led_init)(void);
typedef void (*led_start)(float seconds);
typedef void (*led_stop)(void);

struct led_interface
{
    led_init init;
    led_start start;
    led_stop stop;
};

#endif //LED_INTERFACE_H
