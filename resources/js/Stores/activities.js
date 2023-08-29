import { defineStore } from 'pinia';

export const useActivitiesStore = defineStore('activities', {
  state: () => ({
    activities: [],
  }),
  getters: {
    movingActivities: state => {
      return state.activities.filter(activity => activity.type === 'moving');
    },
  },
  actions: {
    findActivityIndex(activity) {
      return this.activities.findIndex(item => {
        const { from, to, fileName } = item;
        const { key, secret, bucket } = from;
        return (
          key === activity.from.key &&
          secret === activity.from.secret &&
          bucket === activity.from.bucket &&
          to.key === activity.to.key &&
          to.secret === activity.to.secret &&
          fileName === activity.fileName
        );
      });
    },
    addActivity(activity) {
      const exist = this.findActivityIndex(activity);
      if (exist === -1) {
        this.activities.push({
          ...activity,
          type: 'moving',
        });
      }
    },
    markActivityAsCompleted(activity) {
      const index = this.findActivityIndex(activity);
      if (index !== -1) {
        this.activities[index].type = 'completed';
      }
    },
    removeActivity(activity) {
      const index = this.findActivityIndex(activity);
      if (index !== -1) {
        this.activities.splice(index, 1);
      }
    },
    clearActivities() {
      this.activities = [];
    },
  },
});
