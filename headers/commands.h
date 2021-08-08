#ifndef COMMANDS_H
#define COMMANDS_H

#include <string>

using namespace std;

class Commands{
    public:
        // this method contain all instructions to create of a new project
        static void create_project(string project_name);

        // this method contain all instructions to add a resource in project project
        static void add_resource(string resource_name);

        // this method contain all instructions to runserver with the current project
        static void runserver();

        static void about();

        // this method open help menu
        static void help();
};

#endif // !COMMANDS_H