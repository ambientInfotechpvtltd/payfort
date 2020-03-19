var config = {
    map: {
        '*': {
            "ambient_payfort": 'Ambient_Payfort/js/ambient_payfort'
        }
    },
    shim: {
        //dependency third-party lib
        "ambient_payfort": {
             deps: [
                'jquery' //dependency jquery will load first
            ]
        }
    }
};