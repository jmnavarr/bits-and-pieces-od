(function () {
    var shim = {};
    var loadQueue = [];

    shim.add = function (fn) {
        loadQueue.push(fn);
    };

    shim.execute = function () {
        for (var index = 0; index < loadQueue.length; index++) {
            loadQueue[index].call();
        }
    };


    window.amdShim = shim;

}());