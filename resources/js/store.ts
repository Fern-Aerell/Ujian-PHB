import { defineStore } from "pinia";

export const useStore = defineStore('main', {
    state: () => ({
        isExamTime: false,
    }),
    getters: {
        getIsExamTime: (state) => state.isExamTime,
    },
    actions: {
        setIsExamTime(status: boolean) {
            this.isExamTime = status;
        }
    }
});