import axios from 'axios';

let axio = axios.create();

let axio_local = axios.create({
    withCredentials: true,
    baseURL: 'http://localhost/',
    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
});

let ajax = location.hostname === 'localhost' ? axio_local : axio;

ajax.gets = function (url, data) {

    if (!data) {
        return new Promise(function (resolve, reject) {
            ajax.get(url).then(res => {
                if (res && res.data && res.data.code) {
                    // code != 0 => error
                    reject(res.data.data)
                } else {
                    resolve(res.data.data)
                }
            }).catch(() => {
                reject('数据请求失败')
            })
        })
    } else {
        return new Promise(function (resolve, reject) {
            ajax.get(url, {params: data}).then(res => {
                if (res && res.data && res.data.code) {
                    // code != 0 => error
                    reject(res.data.data)
                } else {
                    resolve(res.data.data)
                }
            }).catch(() => {
                reject('数据请求失败')
            })
        })
    }
};

ajax.posts = function (url, data) {
    let param = data || {};
    return new Promise(function (resolve, reject) {
        ajax.post(url, param).then(res => {
            // 输出请求结果，调试用
            window.console.log(res);
            if (res && res.data && res.data.code) {
                // code != 0 => error
                reject(res.data.data)
            } else {
                resolve(res.data.data)
            }
        }).catch((error) => {
            // 输出请求结果，调试用
            window.console.log(error);

            // 正常的结果提示
            reject('数据提交失败')
        })
    })
};

let all = axios.all;
let spread = axios.spread;

export default {ajax, all, spread};