import { request } from '@/utils/request.js'

export default {
    /**
     * 快捷查询字典
     * @param {*} params
     * @returns
     */
    getDict(code) {
        return request({
            url: 'system/dataDict/list?code=' + code,
            method: 'get'
        })
    },

    /**
     * 快捷查询多个字典
     * @param {*} params
     * @returns
     */
    getDicts(codes) {
        return request({
            url: 'system/dataDict/lists?codes=' + codes.join(','),
            method: 'get'
        })
    },

    /**
     * 获取字典数据分页列表
     * @returns
     */
    getPageList(params = {}) {
        return request({
            url: 'system/dataDict/index',
            method: 'get',
            params
        })
    },

    /**
     * 从回收站获取字典数据
     * @returns
     */
    getRecyclePageList(params = {}) {
        return request({
            url: 'system/dataDict/recycle',
            method: 'get',
            params
        })
    },

    /**
     * 添加字典数据
     * @returns
     */
    saveDictData(params = {}) {
        return request({
            url: 'system/dataDict/save',
            method: 'post',
            data: params
        })
    },

    /**
     * 移到回收站
     * @returns
     */
    deletesDictData(ids) {
        return request({
            url: 'system/dataDict/delete' ,
            method: 'post',
						data: {id:ids},
        })
    },

    /**
     * 恢复数据
     * @returns
     */
    recoverysDictData(ids) {
        return request({
            url: 'system/dataDict/recovery' ,
            method: 'put',
						data: {id:ids},
        })
    },

    /**
     * 真实删除
     * @returns
     */
    realDeletesDictData(ids) {
        return request({
            url: 'system/dataDict/realDelete',
            method: 'post',
						data: {id:ids},
        })
    },

    /**
     * 更新数据
     * @returns
     */
    updateDictData(id, params = {}) {
        return request({
            url: 'system/dataDict/update',
            method: 'put',
						data: Object.assign(params,{id:id})
        })
    },

    /**
     * 清空缓存
     * @returns
     */
    clearCache() {
        return request({
            url: 'system/dataDict/clearCache',
            method: 'post'
        })
    },

    /**
     * 更改字典状态
     * @returns
     */
    changeStatus (params = {}) {
        return request({
        url: 'system/dataDict/changeStatus',
        method: 'put',
        data: params
        })
    },

}
