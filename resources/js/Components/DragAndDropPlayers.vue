<template>
  <div class="flex flex-wrap">
    <!-- Unassigned Players -->
    <div class="w-full md:w-1/4 p-2">
      <h3 class="text-lg font-bold mb-2">Unassigned Players</h3>
      <draggable class="list-group space-y-2" v-model="unassignedPlayers" group="players" @end="updateSortOrder">
        <template #item="{ element }">
          <div class="bg-white shadow-md rounded p-2 border" :key="element.id">
            {{ element.name }}
          </div>
        </template>
      </draggable>
    </div>
    <div class="w-full md:w-3/4 p-2">
      <!-- Teams -->
      <template v-for="team in teams" :key="team.id">
        <div class="w-full p-2">
          <h3 class="text-lg font-bold mb-2">{{ team.name }}</h3>
          <draggable class="list-group space-y-2" v-model="team.players" group="players" @end="updateSortOrder">
            <template #item="{ element }">
              <div class="bg-white shadow-md rounded p-2 border" :key="element.id">
                {{ element.name }}
              </div>
            </template>
          </draggable>
        </div>
      </template>
    </div>
  </div>

  <!-- Save Button -->
  <div class="mt-4 p-2">
    <button class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600" @click="saveAssignments">
      Save Assignments
    </button>    
  </div>
</template>


<script>
import draggable from 'vuedraggable';
import axios from "axios";
export default {
  components: { draggable },
  data() {
    return {
      unassignedPlayers: [], // Players not assigned to any team
      teams: [], // Teams with their respective players

    };
  },
  methods: {
    add: function () {
      this.list.push({ name: "Juan" });
    },
    replace: function () {
      this.list = [{ name: "Edgard" }];
    },
    clone: function (el) {
      return {
        name: el.name + " cloned"
      };
    },
    log: function (evt) {
      window.console.log(evt);
    },
    // Fetch unassigned players and teams with players
    fetchPlayersAndTeams() {
      axios.get("/api/teams-players").then((response) => {
        this.unassignedPlayers = response.data.unassignedPlayers || [];
        this.teams = response.data.teams || [];
      });
    },

    // Save current assignments
    saveAssignments() {
      const assignments = this.teams.reduce((acc, team) => {
        acc[team.id] = team.players.map((player) => player.id);
        return acc;
      }, {});
      console.log("Assignments:", this.teams);
      axios
        .post("/api/save-assignments", { assignments })
        .then(() => {
          alert("Assignments saved successfully!");
        })
        .catch((error) => {
          console.error("Error saving assignments:", error);
        });
    },

    // Handle sorting and real-time updates
    updateSortOrder(event) {
      console.log("Updated Sort Order:", event);
    },
  },
  mounted() {
    // Load initial data
    this.fetchPlayersAndTeams();
  },
};
</script>

<style>
/* Basic styling for draggable elements */
.list-group {
  min-height: 100px;
  padding: 10px;
  background-color: #f9f9f9;
  border: 1px solid #ddd;
  border-radius: 4px;
}

.list-group-item {
  cursor: grab;
}

.list-group-item:active {
  cursor: grabbing;
}
</style>
