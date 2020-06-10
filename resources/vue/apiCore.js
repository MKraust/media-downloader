function getRouteDataFromPattern(pattern) {
    const VALUE_PATTERN = /\[.:\w+\]/g;
    const PARAM_PATTERN_NAME = /:\w+/g;

    const valueRegexpStrings = pattern.match(VALUE_PATTERN);
    const valuesNames = pattern.match(PARAM_PATTERN_NAME);
    let params = {};

    if (!valueRegexpStrings || !valuesNames) {
        return params;
    }

    valuesNames.forEach((name, i) => {
        params[name.slice(1)] = valueRegexpStrings[i];
    });

    return params;
}

function serializeParams(params, prefix) {
    const result = [];
    Object.keys(params).forEach(key => {
        const arg = prefix
            ? prefix + '[' + key + ']'
            : key;

        const v = params[key];
        const isObject = v !== null && typeof v === 'object';
        result.push(isObject ? serializeParams(v, arg) : encodeURIComponent(arg) + '=' + encodeURIComponent(v));
    });
    return result.join('&');
}

export function getQueryParamsAsObject(searchString) {
    return searchString
        .split('&')
        .reduce((prev, curr) => {
            let p = curr.split('=');
            prev[decodeURIComponent(p[0])] = decodeURIComponent(p[1]);

            return prev;
        }, {});
}


function generateUrlWithGetParams(pattern, getParams) {
    const patternData = getRouteDataFromPattern(pattern);
    let url = pattern;

    Object.keys(getParams).forEach((paramName) => {
        if (patternData[paramName]) {
            url = url.replace(patternData[paramName], getParams[paramName]);

            delete getParams[paramName];
        }
    });

    const query = serializeParams(getParams);

    return `${url}?${query}`;
}

function convertObjectToFormData(object) {
    let formData = new FormData();

    Object.keys(object).forEach(key => {
        formData.append(key, object[key]);
    });

    return formData;
}

const POST = 'POST';

const filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;

export function fileDownloader(res) {
    const contentDisposition = res.headers.get('Content-Disposition');
    let filename = '';

    if (contentDisposition && contentDisposition.indexOf('attachment') !== -1) {
        const matches = filenameRegex.exec(contentDisposition);

        if (matches !== null && matches[1]) {
            filename = matches[1].replace(/['"]/g, '');
        }
    }

    return res.blob()
        .then((blob) => {
            if (typeof window.navigator.msSaveBlob !== 'undefined') {
                // IE workaround for "HTML7007: One or more blob URLs were revoked by closing the blob for which they were created. These URLs will no longer resolve as the data backing the URL has been freed."
                window.navigator.msSaveBlob(blob, filename);
            } else {
                let URL = window.URL || window.webkitURL;
                let downloadUrl = URL.createObjectURL(blob);

                if (filename) {
                    // use HTML5 a[download] attribute to specify filename
                    let a = document.createElement('a');
                    // safari doesn't support this yet

                    if ('download' in a) {
                        a.href = downloadUrl;
                        a.download = filename;

                        document.body.appendChild(a);

                        a.click();
                    } else if (typeof FileReader !== 'undefined') {
                        let reader = new FileReader();

                        reader.onloadend = function() {
                            if (!window.open(reader.result, '_blank')) {
                                window.location.href = reader.result;
                            }
                        };

                        reader.readAsDataURL(blob);
                    } else {
                        window.location = downloadUrl;
                    }
                } else {
                    window.location = downloadUrl;
                }
            }
        });
}


export async function requestApi({ method = POST, url = '', getParams = {}, postBody = null, errorCallback = () => {}, isFormData = false, isDownloadFile = false }) {
    url = generateUrlWithGetParams(url, getParams);

    const params = { method };

    if (isFormData) {
        params.body = convertObjectToFormData(postBody);
    } else {
        params.headers = { 'Content-Type': 'application/json' };

        if (method === POST || postBody) {
            params.body = JSON.stringify(postBody);
        }
    }

    const response = await fetch(url, params);
    let result;

    if (isDownloadFile) {
        fileDownloader(response);
        return;
    }

    result = await response.json();

    return result;
}
