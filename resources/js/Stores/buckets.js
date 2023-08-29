import { defineStore } from 'pinia'
import axios from 'axios';
import route from "ziggy-js";
export const useBucketsStore = defineStore('buckets', {
  state: () => ({
    buckets: [],
    selectedBucket: null,
  }),
  actions: {
    async fetchBuckets() {
      const { data } = await axios.get(route('buckets.index'));
      this.buckets = data;
      this.selectedBucket = data[0];
    },
    selectBucket(bucket) {
      this.selectedBucket = bucket;
    }
  }
});
