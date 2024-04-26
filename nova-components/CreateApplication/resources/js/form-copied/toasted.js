// Add in toasted
import Toasted     from 'vue-toasted';
import _isNil from 'lodash/isNil'
import _get from 'lodash/get';
import _size from 'lodash/size'

const DEFAULT_ERROR_MESSAGE   = 'Oops! Something went wrong.';
// const RAVEN_EXTERNAL_SRC      = 'https://cdn.ravenjs.com/3.3.0/raven.min.js';
// 

const TOAST_DEFAULTS = {
    position      : 'top-center',
    duration      : 4000,
    containerClass: 'alert-list',
    iconPack      : 'fontawesome',	
};

export const TOAST_ERROR = {
    ...TOAST_DEFAULTS,
    type : 'error',
    icon : 'exclamation',
    className: 'alert alert-dismissible fade show alert-danger'
};

export const TOAST_WARNING = {
    ...TOAST_DEFAULTS,
    type : 'success',
    icon : 'exclamation-triangle',
    className: 'alert alert-dismissible fade show alert-warning'
};

export const TOAST_SUCCESS = {
    ...TOAST_DEFAULTS,
    type : 'success',
    icon : 'check-circle',
    className: 'alert alert-dismissible fade show alert-success'
};

export const TOAST_INFO = {
    ...TOAST_DEFAULTS,
    type : 'info',
    icon : 'info',
    className: 'alert alert-dismissible fade show alert-info'
};

export function showError(error) {

    if ( error && typeof error === 'string' ) {
        Vue.toasted.show(error, TOAST_ERROR);

    } else {
        error.forEach( e => showError(e) );
    }
}

export function catchError(payload) {

    console.log('Toasted caught error:', payload);

    if ( !payload || _isNil(payload) ) {
        return;
    }

    if ( (payload||{}).response ) {

        
        var errors = Object.values( (payload.response.data||{}).errors || {} );

        if ( !_size(errors) ) {
            var errors = _get(payload, 'response.data.message');
        }

        errors && showError(errors);

        return;
    }

    if ( typeof payload === 'string' ) {
        console.log('Showing string error message ', payload);
        return showError(payload);
    }

    if ( payload !== false ) {
        console.error(payload);
    }

    return;
}