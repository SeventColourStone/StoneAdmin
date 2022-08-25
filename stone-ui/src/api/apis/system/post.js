import { request } from '@/utils/request.js'

export default {
  /**
   * 获取岗位分页列表
   * @returns
   */
  getPageList (params = {}) {
    return request({
      url: 'system/post/index',
      method: 'get',
      params
    })
  },

  /**
   * 获取岗位列表
   * @returns
   */
  getList (params = {}) {
    return request({
      url: 'system/post/list',
      method: 'get',
      params
    })
  },

  /**
   * 从回收站获取岗位
   * @returns
   */
  getRecyclePageList (params = {}) {
    return request({
      url: 'system/post/recycle',
      method: 'get',
      params
    })
  },

  /**
   * 添加岗位
   * @returns
   */
  save (params = {}) {
    return request({
      url: 'system/post/save',
      method: 'post',
      data: params
    })
  },

  /**
   * 移到回收站
   * @returns
   */
  deletes (ids) {
    return request({
      url: 'system/post/delete',
			data: {id:ids},
      method: 'post'
    })
  },

  /**
   * 恢复数据
   * @returns
   */
  recoverys (ids) {
    return request({
      url: 'system/post/recovery' ,
			data: {id:ids},
      method: 'put'
    })
  },

  /**
   * 真实删除
   * @returns
   */
  realDeletes (ids) {
    return request({
      url: 'system/post/realDelete',
			data: {id:ids},
      method: 'post'
    })
  },

  /**
   * 更新数据
   * @returns
   */
  update (id, params = {}) {
    return request({
      url: 'system/post/update',
      method: 'put',
			data: Object.assign(params,{id:id})
    })
  },

  /**
   * 更改岗位状态
   * @returns
   */
  changeStatus (params = {}) {
    return request({
      url: 'system/post/changeStatus',
      method: 'put',
      data: params
    })
  },

}
