<template>
  <b-container>
    <!--Prloader-->
    <div v-if="loading" class="cssload-container">
      <div class="cssload-speeding-wheel"></div>
    </div>
    <!--Progress-->
    <div v-if="upload.active" class="progressBar">
      <b-progress :value="upload.current" :max="upload.total" show-progress animated></b-progress>
    </div>
    <!--Header-->
    <b-row>
      <b-col>
        <h5 class="text-center">Simple Laravel File Manager</h5>
      </b-col>
    </b-row>

    <!--Breadcrumb-->
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li v-for="breadcrumb in breadcrumbs" :key="breadcrumb.path" class="breadcrumb-item" :class="{active: breadcrumb.active}">
          <span v-if="breadcrumb.active">{{breadcrumb.name}}</span>
          <a v-else href="#" @click.prevent="changePath(breadcrumb.path)">{{breadcrumb.name}}</a>
        </li>
      </ol>
    </nav>

    <b-row>

      <!--Buttons-->
      <b-col class="button-set" sm="4">
        <b-button id="new-folder" size="sm" variant="outline-secondary">
          <svg class="secondary">
            <use xlink:href="/vendor/muller/filemanager/img/symbols.svg#sprite-newfolder"></use>
          </svg>
        </b-button>
        <b-button @click="refresh" size="sm" variant="outline-primary">
          <svg class="primary">
            <use xlink:href="/vendor/muller/filemanager/img/symbols.svg#sprite-refresh"></use>
          </svg>
        </b-button>
        <b-button @click="$refs.files.click()" size="sm" variant="outline-success">
          <svg class="success">
            <use xlink:href="/vendor/muller/filemanager/img/symbols.svg#sprite-upload"></use>
          </svg>
        </b-button>
        <b-button @click="showDeleteModal" size="sm" variant="outline-danger">
          <svg class="danger">
            <use xlink:href="/vendor/muller/filemanager/img/symbols.svg#sprite-trash"></use>
          </svg>
        </b-button>
        <b-popover target="new-folder"
          ref="createFolderPopover"
          placement="bottomright"
          title="Create New Folder"
          triggers="click"
          content="Test">
          <div>
            <b-input-group size="sm">
              <b-form-input v-model="newFolderName"></b-form-input>
              <b-input-group-append>
                <b-btn @click="createFolder" variant="info">Create</b-btn>
              </b-input-group-append>
            </b-input-group>
          </div>
        </b-popover>
        </div>
        </b-popover>
      </b-col>

      <!--Search-->
      <b-col sm="8">
        <b-input-group size="sm" prepend="Serch">
          <b-form-input></b-form-input>
          <b-input-group-append>
            <b-btn variant="info">Go</b-btn>
          </b-input-group-append>
        </b-input-group>
      </b-col>
    </b-row>

    <!--Grid-->
    <b-row>
      <b-col class="item" v-for="item in items" :key="item.name + '-' + item.type" cols="6" sm="3" md="2">
        <div @click="openFolder(item.name)" v-if="item.type === 'folder'" class="folder">
          <svg>
            <use xlink:href="/vendor/muller/filemanager/img/symbols.svg#sprite-folder"></use>
          </svg>
        </div>
        <div v-else @click="selectFile(item.path, item.name)" class="file">
          <svg>
            <use :xlink:href="'/vendor/muller/filemanager/img/symbols.svg#sprite-'+item.type"></use>
          </svg>
        </div>
        <b-form-checkbox v-model="selection" @click.stop="" :value="item">{{ item.name }}</b-form-checkbox>
      </b-col>
    </b-row>
    <br>
    <b-row>
      <b-col>
        <b-pagination-nav :link-gen="linkGen" size="sm" v-if="numberOfPages > 1" :number-of-pages="numberOfPages" v-model="currentPage" />
      </b-col>
    </b-row>

    <!--Modals-->

    <!--delete-->
    <b-modal
      size="sm"
      header-bg-variant="danger"
      header-text-variant="light"
      ok-variant="danger"
      @ok="deleteItems"
      ref="modal" centered
      title="Delete selected items">
      <p class="my-4">Are you sure you want to remove selected items?</p>
    </b-modal>

    <!--error-->
    <b-modal
      size="sm"
      header-bg-variant="danger"
      header-text-variant="light"
      ok-variant="danger"
      ok-only
      ok-title="Got it"
      centered
      v-model="error.show"
      title="Error">
      <p class="my-4">{{error.message}}</p>
    </b-modal>
    <input style="display: none" @change="processFiles" ref="files" type="file" multiple>
  </b-container>
</template>
<script>
import axios from 'axios'
export default {
  data () {
    return {
      loading: true,
      error: {
        show: false,
        message: ''
      },
      upload: {
        active: false,
        current: 0,
        total: 100
      },
      items: [],
      numberOfPages: 1,
      currentPage: 1,
      path: window.localStorage.getItem('slfm-path') ? window.localStorage.getItem('slfm-path') : '',
      newFolderName: '',
      selection: [],
      files: []
    }
  },
  created () {
    this.getItems(1, this.path)
  },
  computed: {
    breadcrumbs () {
      let _breadcrumbs = this.path.split('/')
      let path = ''
      let breadcrumbs = [
        {
          name: 'Home',
          path: path,
          active: false
        }
      ]
      _breadcrumbs.forEach(item => {
        if(item) {
          path = path + '/' + item
          breadcrumbs.push(
            {
              name: item,
              path: path,
              active: false
            }
          )
        }
      })
      breadcrumbs[breadcrumbs.length - 1].active = true
      return breadcrumbs
    }
  },
  methods: {
    processFiles () {
      let files = this.$refs.files.files
      if(files.length) {
        let data = new FormData()
        for( var i = 0; i < files.length; i++ ){
          let file = files[i];
          data.append('files[' + i + ']', file);
        }
        data.append('path', this.path)
        let vue = this
        let config = {
          onUploadProgress (progressEvent) {
            vue.upload.active = true
            vue.upload.total = progressEvent.total
            vue.upload.current = progressEvent.loaded
          }
        };
        axios.post('slfm/upload', data, config)
          .then(() => {
            this.upload.active = false
            this.refresh()
            this.$refs.files.value = null
          }).catch(err => {
            this.handleError(err)
        })
      }
    },
    deleteItems () {
      this.loading = true
      axios.post('slfm/delete', {items: this.selection})
        .then(() => {
          this.refresh()
          this.loading = false
        }).catch(err => {
        this.loading = false
        this.handleError(err)
      })
    },
    showDeleteModal () {
      if(this.selection.length) {
        this.$refs.modal.show()
      }
    },
    createFolder() {
      this.loading = true
      if (this.newFolderName) {
        axios.put('slfm/folder', {name: this.newFolderName, path: this.path})
        .then(()=>{
          this.refresh()
          this.$refs.createFolderPopover.$emit('close')
          this.newFolderName = ''
        }).catch(err => {
          this.loading = false
          this.$refs.createFolderPopover.$emit('close')
          this.handleError(err)
        })
      }
    },
    handleError (err) {
      this.error.show = true
      this.error.message = err.response.data.message
    },
    refresh () {
      if (this.currentPage > 1) {
        this.currentPage = 1
      }else{
        this.getItems(1, this.path)
      }
      this.selection = []
    },
    getItems (page, path) {
      this.loading = true
      return axios.get('slfm/files', {
        params: {
          page: page,
          path: path
        }
      })
      .then(res => {
        this.items = res.data.data
        this.numberOfPages = res.data.last_page
        window.localStorage.setItem('slfm-path', path)
        this.path = path
        this.loading = false
      })
      .catch(err => {
        this.handleError(err)
        this.loading = false
      })
    },
    selectFile(path, name) {
      path = path ? path + '/' + name : name
      window.callback(path)
    },
    openFolder(name) {
      let path = this.path ? this.path + '/' + name : name
      this.changePath(path)
    },
    changePath (path) {
      this.getItems(1, path)
    },
    linkGen(pageNum) {}
  },
  watch: {
    currentPage (page) {
      this.getItems(page, this.path)
    }
  }
}
</script>
<style>
  .progressBar, .cssload-container {
    display: flex;
    justify-content: center;
    position: absolute;
    width: 100%;
    height: 100%;
    top: 0;
    flex-wrap: nowrap;
    left: 0;
    background-color: rgba(255, 255, 255, 0.8);
    z-index: 10;
  }
  .progressBar .progress {
    align-self: center;
    width: 80%
  }
  .breadcrumb {
    padding: 5px;
    font-size: 14px;
  }
  .button-set .btn{
    padding: 3px;
    font-size: 0;
  }
  .btn svg {
    height: 20px;
    width: 20px;
  }
  svg.primary {
    fill: #007bff;
  }
  svg.secondary {
    fill: #6c757d;
  }
  svg.success {
    fill: #28a745
  }
  svg.danger {
    fill: #dc3545;
  }
  .btn:hover svg {
    fill: #fff;
  }
  .folder{
    fill: #1c9ac5;
  }
  .folder:hover {
    fill: #16688a;
  }
  .file{
    fill: #7f92a8;
  }
  .file:hover{
    fill: #4b6f85;
  }
  .item {
    cursor: pointer;
    overflow: hidden;
    font-size: 13px;
    max-height: 150px;
    text-align: center;
  }
  .item svg{
    width: 50%;
    max-height: 80px;
  }
  a:not([href]):not([tabindex]){
    color: inherit;
  }

  /*Preloader*/
  .cssload-container {
    width: 100%;
    text-align: center;
  }

  .cssload-speeding-wheel {
    display: flex;
    align-self: center;
    width: 56px;
    height: 56px;
    margin: 0 auto;
    border: 3px solid rgb(66,114,237);
    border-radius: 50%;
    border-left-color: transparent;
    border-right-color: transparent;
    animation: cssload-spin 400ms infinite linear;
    -o-animation: cssload-spin 400ms infinite linear;
    -ms-animation: cssload-spin 400ms infinite linear;
    -webkit-animation: cssload-spin 400ms infinite linear;
    -moz-animation: cssload-spin 400ms infinite linear;
  }



  @keyframes cssload-spin {
    100%{ transform: rotate(360deg); transform: rotate(360deg); }
  }

  @-o-keyframes cssload-spin {
    100%{ -o-transform: rotate(360deg); transform: rotate(360deg); }
  }

  @-ms-keyframes cssload-spin {
    100%{ -ms-transform: rotate(360deg); transform: rotate(360deg); }
  }

  @-webkit-keyframes cssload-spin {
    100%{ -webkit-transform: rotate(360deg); transform: rotate(360deg); }
  }

  @-moz-keyframes cssload-spin {
    100%{ -moz-transform: rotate(360deg); transform: rotate(360deg); }
  }

</style>
