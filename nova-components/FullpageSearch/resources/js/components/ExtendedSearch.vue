<script>

import GlobalSearch from './GlobalSearchCopy'
import { Minimum } from 'laravel-nova'

export default {

    extends: GlobalSearch,

    methods: {

        // Here, we just changed the URL
        async fetchResults(search) {
            this.results = []

            if (search !== '') {
                try {
                    const { data: results } = await Minimum(
                        Nova.request().get('/nova-vendor/fullpage-search', {
                            params: { search },
                        })
                    )

                    this.results = results

                    this.loading = false
                } catch (e) {
                    this.loading = false
                    throw e
                }
            }
        },
    }
};
</script>
<style scoped>


    div.relative.z-50.w-full {
        max-width: none;
    }

    div.rounded-lg.absolute {
        position: relative;
        box-shadow: none;
        overflow: auto;
        max-height: none;
        display: flex;
        justify-content: space-between;
    }

    div.rounded-lg.absolute > div {
        flex-grow: 1;
    }

    div.rounded-lg.absolute > div:first-child {
        margin-right: 2em;
    }

    .item-status {
        float: right;
        font-weight: bold;
    }

    ul.list-reset > li > a > div {
        width: 100%;
    }

    ul.list-reset > li {
        margin-bottom: 0.3em;
    }

</style>