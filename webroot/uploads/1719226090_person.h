#ifndef PERSON_H
#define PERSON_H

typedef struct {
    char name[50];
    int salary1;
    int salary2;
} PersonRec;

int processArray(PersonRec *arr, int size, int (*processFun)(PersonRec *rec, int avgSalary));

#endif /* PERSON_H */
