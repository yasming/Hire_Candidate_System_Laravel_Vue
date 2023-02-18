<template>
    <div>
  <div class="p-10">
    <h1 class="text-4xl font-bold">Candidates</h1>
    <div class="p-4 mt-5" :class="{ 'bg-red-100 border-l-4 border-red-500 text-red-700': errorMessage, 'bg-green-100 border-l-4 border-green-500 text-green-700': successMessage }" role="alert" v-if="successMessage || errorMessage">
      <p class="font-bold">{{ this.successMessage || this.errorMessage}}</p>
    </div>
  </div>
        <div class="p-10 grid grid-cols-1 sm:grid-cols-1 md:grid-cols-3 lg:grid-cols-3 xl:grid-cols-3 gap-5">
 <div v-for="candidate in candidates" class="rounded overflow-hidden shadow-lg">
      <img class="w-full" src="/avatar.png" alt="">
  <div class="px-6 py-4"><div class="font-bold text-xl mb-2">{{candidate.name}}</div><p class="text-gray-700 text-base">{{candidate.description}}</p>
                </div>
       <div class="px-6 pt-4 pb-2"><span v-for="strength in JSON.parse(candidate.strengths)" class="inline-block bg-gray-200 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 mr-2 mb-2">{{strength}}</span>
      </div>
  <div class="p-6 float-right">
     <button class="bg-white hover:bg-gray-100 text-gray-800 font-semibold py-2 px-4 border border-gray-400 rounded shadow" v-on:click="contactCandidate(candidate.id)">Contact</button>
    <button v-show="candidate.canHire >= 1 " class="bg-white hover:bg-gray-100 text-gray-800 font-semibold py-2 px-4 border border-gray-400 hover:bg-teal-100 rounded shadow" v-on:click="hireCandidate(candidate.id)">Hire</button>
     </div>
     </div>
        </div>
    </div>
</template>

<script>
export default {
    props:['candidates', 'companyId'],
    data() {
      return {
        successMessage: '',
        errorMessage: '',
      }
    },
    methods: {
      contactCandidate: function (candidateId) {
        console.log(this.candidates)
        axios.post("/candidates-contact", {candidateId, companyId: this.companyId})
            .then((res) => {
              this.successMessage = res.data.message
            })
            .catch((error) => {
              this.errorMessage = error.response.data.message
            });
      },
      hireCandidate: function (candidateId) {
        axios.post("/candidates-hire", {candidateId, companyId: this.companyId})
            .then((res) => {
              this.successMessage = res.data.message
            })
            .catch((error) => {
              this.errorMessage = error.response.data.message
            });
      }
    }
}
</script>
