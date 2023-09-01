import { defineStore } from 'pinia';

export const useActivitiesStore = defineStore('activities', {
  state: () => ({
    activities: [],
  }),
  getters: {
    movingActivities: state => {
      return state.activities.filter(activity => activity.type === 'moving' && activity.status === 'pending');
    },
  },
  actions: {
    findActivityIndex(activity) {
      return this.activities.findIndex(item => {
        const { type, fileName } = item;
        return (
          type === activity.type &&
          fileName === activity.fileName
        );
      });
    },
    addActivity(activity) {
      const exist = this.findActivityIndex(activity);
      if (exist === -1) {
        this.activities.push(activity);
      }
    },
    markActivityAsCompleted(activity) {
      const index = this.findActivityIndex(activity);
      if (index !== -1) {
        this.activities[index].status = 'completed';
      }
    },
    clearActivities() {
      this.activities = [];
    },
  },
});
