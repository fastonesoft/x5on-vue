import axios from 'axios';
import router from '../router'

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
                // 输出请求结果，调试用
                window.console.log(res);
                if (res && res.data && res.data.code) {
                    // code != 0 => error
                    if (res.data.code !== -1) {
                        reject(res.data.data);
                    } else {
                        router.replace('/vlogin');
                    }
                } else {
                    resolve(res.data.data)
                }
            }).catch(error => {
                // 输出请求结果，调试用
                window.console.log('出错：调试信息=>' + error);

                reject('数据请求失败')
            })
        })
    } else {
        return new Promise(function (resolve, reject) {
            ajax.get(url, {params: data}).then(res => {
                // 输出请求结果，调试用
                window.console.log(res);
                if (res && res.data && res.data.code) {
                    // code != 0 => error
                    if (res.data.code !== -1) {
                        reject(res.data.data);
                    } else {
                        router.replace('/vlogin');
                    }
                } else {
                    // success => todo:条件判断要再考虑考虑
                    resolve(res.data.data)
                }
            }).catch(error => {
                // 输出请求结果，调试用
                window.console.log('出错：调试信息=>' + error);

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
                if (res.data.code !== -1) {
                    reject(res.data.data);
                } else {
                    router.replace('/vlogin');
                }
            } else {
                // todo
                resolve(res.data.data)
            }
        }).catch(error => {
            // 输出请求结果，调试用
            window.console.log('出错：调试信息=>' + error);

            // 正常的结果提示
            reject('数据提交失败')
        })
    })
};

let all = axios.all;
let spread = axios.spread;

export default {ajax, all, spread};