import axios from 'axios';

var util = {
    call: function (service, parameters, vue, callback, always) {
        axios.post('api/' + service, parameters, { headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token') } }).then(function (response) {
            if (response.data.message) {
                alert(response.data.message);
            } else {
                callback(response);
            }
            if (always) {
                always();
            }
        })
    },
};

export default util
