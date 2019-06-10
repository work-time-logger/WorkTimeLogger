#ifndef BUZZER_INTERFACE_H
#define BUZZER_INTERFACE_H

typedef void (*buzzer_init)(void);
typedef void (*buzzer_start)(float seconds);
typedef void (*buzzer_stop)(void);

struct buzzer_interface
{
    buzzer_init init;
    buzzer_start start;
    buzzer_stop stop;
};

#endif //BUZZER_INTERFACE_H
