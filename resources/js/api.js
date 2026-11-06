export function getToken() {
    return localStorage.getItem('token');
}

export async function request(method, path, body = null) {
    const headers = { Accept: 'application/json' };
    const token = getToken();

    if (token) headers['Authorization'] = 'Bearer ' + token;
    if (body) headers['Content-Type'] = 'application/json';

    const res = await fetch('/api' + path, {
        method,
        headers,
        body: body ? JSON.stringify(body) : undefined,
    });

    let data = null;
    try {
        data = await res.json();
    } catch (e) {}

    return { ok: res.ok, status: res.status, data };
}