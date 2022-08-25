import { request } from '@/utils/request.js'

export default {

    /**
     * 获取操作日志分页列表
     * @returns
     */
    getPageList (params = {}) {
        return request({
            url: 'system/logs/getOperLogPageList',
            method: 'get',
            params
        })
    },

    /**
     * 删除
     * @returns
     */
    deletes (ids) {
        return request({
            url: 'system/logs/deleteOperLog' ,
            method: 'post',
						data: {id:ids},
        })
    }
}
