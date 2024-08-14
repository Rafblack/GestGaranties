#include <stdio.h>
#include <unistd.h>
#include <stdlib.h>
#include <sys/wait.h>
#include <time.h>
#include <signal.h>
#include <sys/types.h>
#include <dirent.h>
#include <string.h>

// Function to generate a random sleep time between 20 to 30 seconds
int getRandomSleepTime() {
    return rand() % 11 + 20;  // Generates a random number between 20 and 30
}

// Function to list all currently running processes
void listRunningProcesses() {
    DIR *dir;
    struct dirent *entry;

    dir = opendir("/proc");
    if (dir == NULL) {
        perror("opendir failed");
        exit(EXIT_FAILURE);
    }

    printf("List of currently running processes:\n");

    while ((entry = readdir(dir)) != NULL) {
        if (entry->d_type == DT_DIR) {
            if (atoi(entry->d_name) != 0) {  // Check if the directory name is a process ID
                printf("%s\n", entry->d_name);
            }
        }
    }

    closedir(dir);
}

int main() {
    int status, parent, children[5], sleepTimes[5];
    pid_t child;
    srand(time(NULL));  // Seed for generating random numbers

    printf("I am the parent (PID=%d)\n", parent = getpid());
    printf("I am spawning 5 children  ...\n");
  
    // Create 5 child processes
    for (int i = 0; i < 5; i++) {
        children[i] = fork();
        if (children[i] == 0) {
            // Child process
            sleepTimes[i] = getRandomSleepTime(); // Generate random sleep time
            printf("     I am a child (PID=%d) ... I will sleep for %d sec\n", getpid(), sleepTimes[i]);
            sleep(sleepTimes[i]);
            printf("     I am awake!  Process %d terminating.\n", getpid());
            exit(0);
        }
    }

    // Prompt user to input the desired child PID to wait for
    printf("Enter the PID of the child process you want to wait for: ");
    scanf("%d", &child);
    waitpid(child, &status, 0);
    printf("It looks like my child (PID=%d) has terminated.\n", child);

    // Sending SIGTERM signal to terminate remaining child processes
    for (int i = 0; i < 5; i++) {
        if (children[i] != child && children[i] > 0) {
            kill(children[i], SIGTERM);
        }
    }

    printf("All children have terminated. Parent process %d terminating.\n", getpid());
    return 0;
}
