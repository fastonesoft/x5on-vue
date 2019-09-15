// 日期格式
let dateFormat = function (value, fmt) {
    if (value instanceof Date) {
        let o = {
            'M+': value.getMonth() + 1,
            'd+': value.getDate(),
            'h+': value.getHours(),
            'm+': value.getMinutes(),
            's+': value.getSeconds(),
            'q+': Math.floor((value.getMonth() + 3) / 3),
            'S+': value.getMilliseconds()
        };
        if (/(y+)/.test(fmt)) {
            fmt = fmt.replace(RegExp.$1, ('0000' + value.getFullYear()).substr(-RegExp.$1.length));
        }
        for (let k in o) {
            if (new RegExp('(' + k + ')').test(fmt)) {
                fmt = fmt.replace(RegExp.$1, RegExp.$1.length === 1 ? o[k] : ('00' + o[k]).substr(-RegExp.$1.length));
            }
        }
        return fmt;
    } else {
        return value;
    }

};

// 数据分页
let pageData = function (datas, index, size) {
    if (datas.length <= size) {
        return datas.slice(0, size)
    } else {
        let _start = (index - 1) * size;
        let _end = index * size;
        return datas.slice(_start, _end);
    }
};

// 写入、删除本地session
let stateClear = function () {
    sessionStorage.clear();
};

let stateWrite = function (state) {
    sessionStorage.setItem("xc-store", JSON.stringify(state))
};

// 数组增删改
let arrsDel = function (arrs, key, keyValue) {
    arrs.forEach((item, index) => {
        if (item[key] === keyValue) {
            arrs.splice(index, 1)
        }
    });
    return arrs;
};

let arrsEdit = function (arrs, key, keyValue, value) {
    arrs.forEach((item, index) => {
        if (item[key] === keyValue) {
            arrs.splice(index, 1, value)
        }
    });
    return arrs;
};

export default {
    dateFormat, pageData,
    stateClear, stateWrite,
    arrsDel, arrsEdit,
};