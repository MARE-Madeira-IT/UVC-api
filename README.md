# Underwater Survey API

Underwater Survey is a platform for storing transect's data

# Setup

## !IMPORTANT!

Before seeding, it is important that the following commands have been executed in Wave:

```sh
$ php artisan permissions:create
$ php artisan admin:create --dummy
$ php artisan guest:create
```

### Env File

```sh
$ cp .env-example .env
$ php artisan key:generate
$ php artisan jwt:secret
```

### Edit .env

| Database      | Wave server          | Mail              |
| ------------- | -------------------- | ----------------- |
| DB_CONNECTION | WAVE_SERVER_HOST     | MAIL_MAILER       |
| DB_HOST       | WAVE_SERVER_PORT     | MAIL_HOST         |
| DB_PORT       | WAVE_SERVER_USERNAME | MAIL_PORT         |
| DB_DATABASE   | WAVE_SERVER_PASSWORD | MAIL_USERNAME     |
| DB_USERNAME   |                      | MAIL_PASSWORD     |
| DB_PASSWORD   |                      | MAIL_ENCRYPTION   |
|               |                      | MAIL_FROM_ADDRESS |
|               |                      | MAIL_FROM_NAME    |

## Dependencies

```sh
$ composer install
```

## Cron job vs queue worker (optional)
> Send emails
> Deleting photos on ai catalogue
> ARDome decrement active users

#### Cron Job

Update Kernel.php schedule() method:
 - \$schedule->command('queue:work')->everyMinute()->withoutOverlapping();

Add the following cron job to server
```sh
$ crontab -e
$ * * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```
#### Queue worker
> Path of supervisor conf.d may change

```sh
$ sudo apt-get install supervisor
$ cd /etc/supervisor/conf.d
```
Within this directory, you may create any number of configuration files that instruct supervisor how your processes should be monitored. For example, a wave-worker.conf file that starts and monitors a queue:work process:

    [program:wave-worker]
    process_name=%(program_name)s_%(process_num)02d
    command=php /path-to-project/artisan queue:work
    autostart=true
    autorestart=true
    user= user-name-here
    numprocs=8
    redirect_stderr=true
    stdout_logfile=/path-to-project/worker.log

Once the configuration file has been created, you may update the Supervisor configuration and start the processes using the following commands:

```sh
$ sudo supervisorctl reread
$ sudo supervisorctl update
$ sudo supervisorctl start wave-worker:*
```
In case new configuration files have not been found update / create the supervisord.conf file at /ert/supervisor, making sure the last line to include files is present and not commented.

    ; Sample supervisor config file.
    ;
    ; For more information on the config file, please see:
    ; http://supervisord.org/configuration.html
    ;
    ; Notes:
    ;  - Shell expansion ("~" or "$HOME") is not supported.  Environment
    ;    variables can be expanded using this syntax: "%(ENV_HOME)s".
    ;  - Quotes around values are not supported, except in the case of
    ;    the environment= options as shown below.
    ;  - Comments must have a leading space: "a=b ;comment" not "a=b;comment".
    ;  - Command will be truncated if it looks like a config file comment, e.g.
    ;    "command=bash -c 'foo ; bar'" will truncate to "command=bash -c 'foo ".
    ;
    ; Warning:
    ;  Paths throughout this example file use /tmp because it is available on most
    ;  systems.  You will likely need to change these to locations more appropriate
    ;  for your system.  Some systems periodically delete older files in /tmp.
    ;  Notably, if the socket file defined in the [unix_http_server] section below
    ;  is deleted, supervisorctl will be unable to connect to supervisord.

    [unix_http_server]
    file=/tmp/supervisor.sock   ; the path to the socket file
    ;chmod=0700                 ; socket file mode (default 0700)
    ;chown=nobody:nogroup       ; socket file uid:gid owner
    ;username=user              ; default is no username (open server)
    ;password=123               ; default is no password (open server)

    ; Security Warning:
    ;  The inet HTTP server is not enabled by default.  The inet HTTP server is
    ;  enabled by uncommenting the [inet_http_server] section below.  The inet
    ;  HTTP server is intended for use within a trusted environment only.  It
    ;  should only be bound to localhost or only accessible from within an
    ;  isolated, trusted network.  The inet HTTP server does not support any
    ;  form of encryption.  The inet HTTP server does not use authentication
    ;  by default (see the username= and password= options to add authentication).
    ;  Never expose the inet HTTP server to the public internet.

    ;[inet_http_server]         ; inet (TCP) server disabled by default
    ;port=127.0.0.1:9001        ; ip_address:port specifier, *:port for all iface
    ;username=user              ; default is no username (open server)
    ;password=123               ; default is no password (open server)

    [supervisord]
    logfile=/tmp/supervisord.log ; main log file; default $CWD/supervisord.log
    logfile_maxbytes=50MB        ; max main logfile bytes b4 rotation; default 50MB
    logfile_backups=10           ; # of main logfile backups; 0 means none, default 10
    loglevel=info                ; log level; default info; others: debug,warn,trace
    pidfile=/tmp/supervisord.pid ; supervisord pidfile; default supervisord.pid
    nodaemon=false               ; start in foreground if true; default false
    silent=false                 ; no logs to stdout if true; default false
    minfds=1024                  ; min. avail startup file descriptors; default 1024
    minprocs=200                 ; min. avail process descriptors;default 200
    ;umask=022                   ; process file creation umask; default 022
    ;user=supervisord            ; setuid to this UNIX account at startup; recommended if root
    ;identifier=supervisor       ; supervisord identifier, default is 'supervisor'
    ;directory=/tmp              ; default is not to cd during start
    ;nocleanup=true              ; don't clean up tempfiles at start; default false
    ;childlogdir=/tmp            ; 'AUTO' child log dir, default $TEMP
    ;environment=KEY="value"     ; key value pairs to add to environment
    ;strip_ansi=false            ; strip ansi escape codes in logs; def. false

    ; The rpcinterface:supervisor section must remain in the config file for
    ; RPC (supervisorctl/web interface) to work.  Additional interfaces may be
    ; added by defining them in separate [rpcinterface:x] sections.

    [rpcinterface:supervisor]
    supervisor.rpcinterface_factory = supervisor.rpcinterface:make_main_rpcinterface

    ; The supervisorctl section configures how supervisorctl will connect to
    ; supervisord.  configure it match the settings in either the unix_http_server
    ; or inet_http_server section.

    [supervisorctl]
    serverurl=unix:///tmp/supervisor.sock ; use a unix:// URL  for a unix socket
    ;serverurl=http://127.0.0.1:9001 ; use an http:// url to specify an inet socket
    ;username=chris              ; should be same as in [*_http_server] if set
    ;password=123                ; should be same as in [*_http_server] if set
    ;prompt=mysupervisor         ; cmd line prompt (default "supervisor")
    ;history_file=~/.sc_history  ; use readline history if available

    ; The sample program section below shows all possible program subsection values.
    ; Create one or more 'real' program: sections to be able to control them under
    ; supervisor.

    ;[program:theprogramname]
    ;command=/bin/cat              ; the program (relative uses PATH, can take args)
    ;process_name=%(program_name)s ; process_name expr (default %(program_name)s)
    ;numprocs=1                    ; number of processes copies to start (def 1)
    ;directory=/tmp                ; directory to cwd to before exec (def no cwd)
    ;umask=022                     ; umask for process (default None)
    ;priority=999                  ; the relative start priority (default 999)
    ;autostart=true                ; start at supervisord start (default: true)
    ;startsecs=1                   ; # of secs prog must stay up to be running (def. 1)
    ;startretries=3                ; max # of serial start failures when starting (default 3)
    ;autorestart=unexpected        ; when to restart if exited after running (def: unexpected)
    ;exitcodes=0                   ; 'expected' exit codes used with autorestart (default 0)
    ;stopsignal=QUIT               ; signal used to kill process (default TERM)
    ;stopwaitsecs=10               ; max num secs to wait b4 SIGKILL (default 10)
    ;stopasgroup=false             ; send stop signal to the UNIX process group (default false)
    ;killasgroup=false             ; SIGKILL the UNIX process group (def false)
    ;user=chrism                   ; setuid to this UNIX account to run the program
    ;redirect_stderr=true          ; redirect proc stderr to stdout (default false)
    ;stdout_logfile=/a/path        ; stdout log path, NONE for none; default AUTO
    ;stdout_logfile_maxbytes=1MB   ; max # logfile bytes b4 rotation (default 50MB)
    ;stdout_logfile_backups=10     ; # of stdout logfile backups (0 means none, default 10)
    ;stdout_capture_maxbytes=1MB   ; number of bytes in 'capturemode' (default 0)
    ;stdout_events_enabled=false   ; emit events on stdout writes (default false)
    ;stdout_syslog=false           ; send stdout to syslog with process name (default false)
    ;stderr_logfile=/a/path        ; stderr log path, NONE for none; default AUTO
    ;stderr_logfile_maxbytes=1MB   ; max # logfile bytes b4 rotation (default 50MB)
    ;stderr_logfile_backups=10     ; # of stderr logfile backups (0 means none, default 10)
    ;stderr_capture_maxbytes=1MB   ; number of bytes in 'capturemode' (default 0)
    ;stderr_events_enabled=false   ; emit events on stderr writes (default false)
    ;stderr_syslog=false           ; send stderr to syslog with process name (default false)
    ;environment=A="1",B="2"       ; process environment additions (def no adds)
    ;serverurl=AUTO                ; override serverurl computation (childutils)

    ; The sample eventlistener section below shows all possible eventlistener
    ; subsection values.  Create one or more 'real' eventlistener: sections to be
    ; able to handle event notifications sent by supervisord.

    ;[eventlistener:theeventlistenername]
    ;command=/bin/eventlistener    ; the program (relative uses PATH, can take args)
    ;process_name=%(program_name)s ; process_name expr (default %(program_name)s)
    ;numprocs=1                    ; number of processes copies to start (def 1)
    ;events=EVENT                  ; event notif. types to subscribe to (req'd)
    ;buffer_size=10                ; event buffer queue size (default 10)
    ;directory=/tmp                ; directory to cwd to before exec (def no cwd)
    ;umask=022                     ; umask for process (default None)
    ;priority=-1                   ; the relative start priority (default -1)
    ;autostart=true                ; start at supervisord start (default: true)
    ;startsecs=1                   ; # of secs prog must stay up to be running (def. 1)
    ;startretries=3                ; max # of serial start failures when starting (default 3)
    ;autorestart=unexpected        ; autorestart if exited after running (def: unexpected)
    ;exitcodes=0                   ; 'expected' exit codes used with autorestart (default 0)
    ;stopsignal=QUIT               ; signal used to kill process (default TERM)
    ;stopwaitsecs=10               ; max num secs to wait b4 SIGKILL (default 10)
    ;stopasgroup=false             ; send stop signal to the UNIX process group (default false)
    ;killasgroup=false             ; SIGKILL the UNIX process group (def false)
    ;user=chrism                   ; setuid to this UNIX account to run the program
    ;redirect_stderr=false         ; redirect_stderr=true is not allowed for eventlisteners
    ;stdout_logfile=/a/path        ; stdout log path, NONE for none; default AUTO
    ;stdout_logfile_maxbytes=1MB   ; max # logfile bytes b4 rotation (default 50MB)
    ;stdout_logfile_backups=10     ; # of stdout logfile backups (0 means none, default 10)
    ;stdout_events_enabled=false   ; emit events on stdout writes (default false)
    ;stdout_syslog=false           ; send stdout to syslog with process name (default false)
    ;stderr_logfile=/a/path        ; stderr log path, NONE for none; default AUTO
    ;stderr_logfile_maxbytes=1MB   ; max # logfile bytes b4 rotation (default 50MB)
    ;stderr_logfile_backups=10     ; # of stderr logfile backups (0 means none, default 10)
    ;stderr_events_enabled=false   ; emit events on stderr writes (default false)
    ;stderr_syslog=false           ; send stderr to syslog with process name (default false)
    ;environment=A="1",B="2"       ; process environment additions
    ;serverurl=AUTO                ; override serverurl computation (childutils)

    ; The sample group section below shows all possible group values.  Create one
    ; or more 'real' group: sections to create "heterogeneous" process groups.

    ;[group:thegroupname]
    ;programs=progname1,progname2  ; each refers to 'x' in [program:x] definitions
    ;priority=999                  ; the relative start priority (default 999)

    ; The [include] section can just contain the "files" setting.  This
    ; setting can list multiple files (separated by whitespace or
    ; newlines).  It can also contain wildcards.  The filenames are
    ; interpreted as relative to this file.  Included files *cannot*
    ; include files themselves.

    [include]
    files = /etc/supervisor/conf.d/*.conf


## Start server

```sh
$ php artisan serve
$ php artisan migrate:fresh --seed
$ php artisan db:seed -Seeder #Needs to be after creating an admin
$ php artisan schedule:run **in case you implemented a queue worker**
```

## Login on website

 - **Username**: admin@admin.wave
 - **Password**: admin


 ## Server permisions

```sh
$ sudo usermod -a -G www-data **your-server-username**
$ sudo chmod -R 775 /path/to/your/project/storage
$ sudo chown -R www-data:www-data /path/to/your/project/storage
```
