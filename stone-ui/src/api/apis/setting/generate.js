import { request } from '@/utils/request.js'

export default {
  /**
   * 获取代码生成列表
   * @returns
   */
  getPageList (params = {}) {
    return request({
      url: 'setting/code/index',
      method: 'get',
      params
    })
  },

  /**
   * 删除
   * @returns
   */
  deletes (params) {
    return request({
      url: 'setting/code/delete' ,
      method: 'post',
			data: {id:params}
    })
  },

  /**
   * 编辑生成信息
   * @returns
   */
  update (data = {}) {
    return request({
      url: 'setting/code/update',
      method: 'post',
      data
    })
  },

  readTable (params = {}) {
    return request({
      url: 'setting/code/readTable',
      method: 'get',
      params
    })
  },

  /**
   * 生成代码
   * @returns
   */
  generate (ids = {}) {
    return request({
      url: 'setting/code/generate',
      method: 'post',
			data: {id:ids},
      responseType: 'blob',
    })
  },

  /**
   * 装载数据表
   * @returns
   */
  loadTable (data = {}) {
    return request({
      url: 'setting/code/loadTable',
      method: 'post',
      data
    })
  },

  /**
   * 同步数据表
   * @returns
   */
  sync (data) {
    return request({
      url: 'setting/code/sync' ,
      method: 'put',
			data: {id:data},
    })
  },

  /**
   * 同步数据表
   * @returns
   */
  preview (id) {
    return request({
      url: 'setting/code/preview',
      method: 'post',
			data: {id:id},
    })
  },

  // 获取表中字段信息
  getTableColumns(params = {}) {
    return request({
      url: 'setting/code/getTableColumns',
      method: 'post',
      data:params
    })
  },

  // 获取所有模型
  getModels() {
    return request({
      url: 'setting/code/getModels',
      method: 'get',
    })
  }
}
