(function () {
    /*
    |--------------------------------------------------------------------------
    | Idle Timeout
    |--------------------------------------------------------------------------
    |
    | 10 menit = 600.000 ms
    |
    */

    const IDLE_LIMIT = 10 * 60 * 1000;

    let idleTimer = null;

    /*
    |--------------------------------------------------------------------------
    | Logout User
    |--------------------------------------------------------------------------
    */

    function logoutUser() {
        const logoutForm =
            document.getElementById(
                'idleLogoutForm'
            );

        if (logoutForm) {
            logoutForm.submit();
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Fallback
        |--------------------------------------------------------------------------
        */

        window.location.href = '/login';
    }


    /*
    |--------------------------------------------------------------------------
    | Reset Timer Jika Ada Aktivitas
    |--------------------------------------------------------------------------
    */

    function resetIdleTimer() {
        if (idleTimer) {
            clearTimeout(
                idleTimer
            );
        }

        idleTimer = setTimeout(
            logoutUser,
            IDLE_LIMIT
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Aktivitas Yang Dianggap User Aktif
    |--------------------------------------------------------------------------
    */

    const activityEvents = [
        'mousedown',
        'keydown',
        'scroll',
        'touchstart',
        'click'
    ];


    activityEvents.forEach(
        function (eventName) {

            document.addEventListener(
                eventName,
                resetIdleTimer,
                true
            );

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Mulai Timer Saat Halaman Dibuka
    |--------------------------------------------------------------------------
    */

    resetIdleTimer();
})();