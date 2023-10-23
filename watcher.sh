#!/bin/bash

#Full path to folder including /home/{username}/
folder_to_watch=""

#Put your full path to downloaded script instead of {path} stub, path must be with /home/{username}/
command_to_run="php {path}/files_sorting_script.php";

delay=180

inotifywait -m -r -e create "$folder_to_watch" |
while read path action file; do
    if [ "$action" = "CREATE" ]; then

      sleep delay

        $command_to_run
    fi
done
