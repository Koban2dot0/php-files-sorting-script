#!/bin/bash

folder_to_watch="/home/koban/Downloads"

command_to_run="php /home/koban/PhpstormProjects/sorting-command/files_sorting_script.php";

delay=180

inotifywait -m -r -e create "$folder_to_watch" |
while read path action file; do
    if [ "$action" = "CREATE" ]; then

      sleep delay

        $command_to_run
    fi
done
