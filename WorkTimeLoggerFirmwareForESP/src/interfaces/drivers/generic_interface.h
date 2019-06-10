#ifndef GENERIC_INTERFACE_H
#define GENERIC_INTERFACE_H

typedef void (*generic_init)(void);
typedef void (*generic_event)(void);

struct generic_interface
{
    generic_init init;
    generic_event event;
};

#endif //GENERIC_INTERFACE_H
