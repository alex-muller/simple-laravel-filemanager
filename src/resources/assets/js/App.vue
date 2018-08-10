<template>
  <b-container>
    <!--Header-->
    <b-row>
      <b-col>
        <p>Simple Laravel File Manager</p>
      </b-col>
    </b-row>
    <!--Pagination-->
    <b-row>
      <b-col>
        <b-pagination-nav :link-gen="linkGen" size="sm" v-if="numberOfPages > 1" :number-of-pages="numberOfPages" v-model="currentPage" />
      </b-col>
    </b-row>
    <!--Grid-->
    <b-row>
      <b-col v-for="item in items" :key="item.name + '-' + item.type" cols="6" sm="3" md="2">
        <div v-if="item.type === 'folder'" class="folder item">
          <svg>
            <use xlink:href="/vendor/muller/filemanager/img/symbols.svg#sprite-folder"></use>
          </svg>
          <b-form-checkbox value="orange">Orange OrangeOrange Orange Orange</b-form-checkbox>
        </div>
        <div v-if="item.type === 'file'" class="file item">
          <svg>
            <use xlink:href="/vendor/muller/filemanager/img/symbols.svg#sprite-file"></use>
          </svg>
          <b-form-checkbox value="orange">Orange</b-form-checkbox>
        </div>
      </b-col>
    </b-row>
  </b-container>
</template>
<script>
export default {
  data () {
    return {
      items: [],
      numberOfPages: 1,
      currentPage: 1
    }
  },
  created () {
    this.getItems(1)
  },
  methods: {
    getItems (page) {
      this.axios.get('slfm/files', {
        params: {
          page: page
        }
      })
      .then(res => {
        this.items = res.data.data
        this.numberOfPages = res.data.last_page
      })
    },
    linkGen(pageNum) {

    }
  },
  watch: {
    currentPage (page) {
      this.getItems(page)
    }
  }
}
</script>
<style>
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
    margin: 0 5px;
  }
  .item svg{
    width: 100%;
    max-width: 120px;
    height: 120px;
  }


</style>
