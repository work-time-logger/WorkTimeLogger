#include "void_generic.h"

void void_generic_init(void);
void void_generic_event(void);

static const struct generic_interface void_generic = {
    void_generic_init,
    void_generic_event
};

const struct generic_interface *void_generic_get() {
    return &void_generic;
}

void void_generic_init(void){

}

void void_generic_event(void){

}