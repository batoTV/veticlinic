@extends('layouts.guest')

@section('content')
    <div x-data="{
        clientStatus: 'new',
        numberOfPets: 1,
        pets: Array.from({ length: 1 }, (_, i) => ({
            name: '',
            species: '',
            breed: '',
            birth_date: '',
            gender: 'Male',
            allergies: '',
            chief_complaint: ''
        })),
        findName: '',
        findPhone: '',
        foundOwner: null,
        searchMessage: '',
        isLoading: false,
        errors: @json($errors->toArray()),
        addPet() {
            this.pets.push({
                name: '',
                species: '',
                breed: '',
                birth_date: '',
                gender: 'Male',
                allergies: '',
                chief_complaint: ''
            });
            this.numberOfPets = this.pets.length;
        },
        removePet(index) {
            this.pets.splice(index, 1);
            this.numberOfPets = this.pets.length;
        },
        updatePetCount() {
            const count = parseInt(this.numberOfPets);
            if (count < 1) {
                this.numberOfPets = 1;
                return;
            }
            const currentCount = this.pets.length;
            if (count > currentCount) {
                for (let i = currentCount; i < count; i++) {
                    this.addPet();
                }
            } else if (count < currentCount) {
                this.pets.splice(count);
            }
        },
        findOwner() {
            this.isLoading = true;
            this.searchMessage = '';
            fetch('{{ route('client.find') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
                },
                body: JSON.stringify({ name: this.findName, phone_number: this.findPhone })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.foundOwner = data.owner;
                    this.searchMessage = data.message;
                } else {
                    this.foundOwner = null;
                    this.searchMessage = data.message;
                }
                this.isLoading = false;
            })
            .catch(() => {
                this.searchMessage = 'An error occurred. Please try again.';
                this.isLoading = false;
            });
        },
        resetForm() {
            this.findName = '';
            this.findPhone = '';
            this.foundOwner = null;
            this.searchMessage = '';
        },
        init() {
            this.$watch('clientStatus', () => this.resetForm());
        }
    }" x-init="init()">
        
        <form id="registrationForm" method="POST" action="{{ route('client.store') }}">
            @csrf
            
            <input type="hidden" name="client_status" x-model="clientStatus">

            <!-- General Error Message -->
            <div x-show="errors.general" class="mb-4 p-4 bg-red-100 text-red-700 rounded-md" x-text="errors.general ? errors.general[0] : ''"></div>


            <!-- Client Status Selection -->
            <div class="mb-6">
                <h2 class="text-xl font-bold text-gray-800 mb-2">Client Status</h2>
                <div class="flex items-center space-x-8">
                    <label class="flex items-center">
                        <input type="radio" name="status_option" value="new" x-model="clientStatus" class="form-radio h-5 w-5 text-indigo-600">
                        <span class="ml-2 text-gray-700">I am a new client</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" name="status_option" value="existing" x-model="clientStatus" class="form-radio h-5 w-5 text-indigo-600">
                        <span class="ml-2 text-gray-700">I am an existing client</span>
                    </label>
                </div>
            </div>

            <!-- New Client Form -->
            <div x-show="clientStatus === 'new'" x-transition>
                <h2 class="text-xl font-bold text-gray-800 mb-4">Your Information</h2>
                <div>
                    <label for="name" class="block font-medium text-sm text-gray-700">Full Name</label>
                    <input id="name" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" type="text" name="name" value="{{ old('name') }}">
                    <div x-show="errors.name" x-text="errors.name ? errors.name[0] : ''" class="text-red-500 text-sm mt-1"></div>
                </div>
                <div class="mt-4">
                    <label for="email" class="block font-medium text-sm text-gray-700">Email</label>
                    <input id="email" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" type="email" name="email" value="{{ old('email') }}">
                    <div x-show="errors.email" x-text="errors.email ? errors.email[0] : ''" class="text-red-500 text-sm mt-1"></div>
                </div>
                <div class="mt-4">
                    <label for="phone_number" class="block font-medium text-sm text-gray-700">Phone Number</label>
                    <input id="phone_number" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" type="text" name="phone_number" value="{{ old('phone_number') }}">
                    <div x-show="errors.phone_number" x-text="errors.phone_number ? errors.phone_number[0] : ''" class="text-red-500 text-sm mt-1"></div>
                </div>
                <div class="mt-4">
                    <label for="address" class="block font-medium text-sm text-gray-700">Address</label>
                    <input id="address" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" type="text" name="address" value="{{ old('address') }}">
                    <div x-show="errors.address" x-text="errors.address ? errors.address[0] : ''" class="text-red-500 text-sm mt-1"></div>
                </div>
            </div>
            
            <!-- Existing Client Form -->
            <div x-show="clientStatus === 'existing'" x-transition>
                <h2 class="text-xl font-bold text-gray-800 mb-4">Find Your Record</h2>
                <div>
                    <label for="find_name" class="block font-medium text-sm text-gray-700">Full Name (or First/Last Name)</label>
                    <input id="find_name" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" type="text" x-model="findName">
                </div>
                <div class="mt-4">
                    <label for="find_phone" class="block font-medium text-sm text-gray-700">Phone Number</label>
                    <input id="find_phone" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" type="text" x-model="findPhone">
                </div>
                <div class="mt-4">
                    <button type="button" @click="findOwner()" x-text="isLoading ? 'Searching...' : 'Find Me'" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                        Find Me
                    </button>
                </div>

                <div x-show="searchMessage" x-text="searchMessage" class="mt-4 p-4 rounded-md" :class="foundOwner ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"></div>
                <input type="hidden" name="owner_id" x-bind:value="foundOwner ? foundOwner.id : ''">
            </div>

            <!-- Pet Information Section -->
            <div class="mt-8 pt-6 border-t" x-show="clientStatus === 'new' || foundOwner">
                 <div class="flex justify-between items-center">
                     <h2 class="text-xl font-bold text-gray-800">Pet Information</h2>
                     <div class="flex items-center space-x-2">
                        <label for="numberOfPets" class="block font-medium text-sm text-gray-700">Number of Pets:</label>
                        <input id="numberOfPets" type="number" class="w-20 border-gray-300 rounded-md shadow-sm" x-model.number="numberOfPets" @input="updatePetCount()" min="1" max="10"/>
                     </div>
                </div>
                
                <template x-for="(pet, index) in pets" :key="index">
                    <div class="border p-4 rounded-md mt-4">
                        <div class="flex justify-between items-center">
                            <h3 class="font-bold text-lg mb-2" x-text="'Pet ' + (index + 1)"></h3>
                            <button type="button" x-show="index > 0" @click="removePet(index)" class="text-red-500 hover:text-red-700">&times; Remove</button>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label :for="'pet_name_' + index" class="block font-medium text-sm text-gray-700">Pet's Name</label>
                                <input :id="'pet_name_' + index" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" type="text" :name="'pets[' + index + '][name]'" x-model="pet.name">
                                <div x-show="errors['pets.' + index + '.name']" x-text="errors['pets.' + index + '.name'] ? errors['pets.' + index + '.name'][0] : ''" class="text-red-500 text-sm mt-1"></div>
                            </div>
                            <div>
                               <label :for="'pet_species_' + index" class="block font-medium text-sm text-gray-700">Species (e.g., Dog, Cat)</label>
                               <input :id="'pet_species_' + index" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" type="text" :name="'pets[' + index + '][species]'" x-model="pet.species">
                               <div x-show="errors['pets.' + index + '.species']" x-text="errors['pets.' + index + '.species'] ? errors['pets.' + index + '.species'][0] : ''" class="text-red-500 text-sm mt-1"></div>
                            </div>
                            <div>
                               <label :for="'pet_breed_' + index" class="block font-medium text-sm text-gray-700">Breed</label>
                               <input :id="'pet_breed_' + index" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" type="text" :name="'pets[' + index + '][breed]'" x-model="pet.breed">
                               <div x-show="errors['pets.' + index + '.breed']" x-text="errors['pets.' + index + '.breed'] ? errors['pets.' + index + '.breed'][0] : ''" class="text-red-500 text-sm mt-1"></div>
                            </div>
                            <div>
                               <label :for="'pet_birth_date_' + index" class="block font-medium text-sm text-gray-700">Birth Date</label>
                               <input :id="'pet_birth_date_' + index" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" type="date" :name="'pets[' + index + '][birth_date]'" x-model="pet.birth_date">
                               <div x-show="errors['pets.' + index + '.birth_date']" x-text="errors['pets.' + index + '.birth_date'] ? errors['pets.' + index + '.birth_date'][0] : ''" class="text-red-500 text-sm mt-1"></div>
                            </div>
                            <div class="col-span-1 md:col-span-2">
                               <label :for="'pet_gender_' + index" class="block font-medium text-sm text-gray-700">Gender</label>
                               <select :id="'pet_gender_' + index" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" :name="'pets[' + index + '][gender]'" x-model="pet.gender">
                                    <option>Male</option>
                                    <option>Female</option>
                               </select>
                            </div>
                            <div class="col-span-1 md:col-span-2">
                               <label :for="'pet_allergies_' + index" class="block font-medium text-sm text-gray-700">Allergies (if any)</label>
                               <textarea :id="'pet_allergies_' + index" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" :name="'pets[' + index + '][allergies]'" x-model="pet.allergies"></textarea>
                            </div>
                            <div class="col-span-1 md:col-span-2" x-show="index === 0">
                                <label :for="'pet_chief_complaint_' + index" class="block font-medium text-sm text-gray-700">Chief Complaint / Reason for Visit</label>
                                <textarea :id="'pet_chief_complaint_' + index" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" :name="'pets[' + index + '][chief_complaint]'" x-model="pet.chief_complaint"></textarea>
                            </div>
                        </div>
                    </div>
                </template>
            </div>


            <div class="flex items-center justify-end mt-8">
                <button type="button" @click="document.getElementById('confirmationModal').style.display = 'block'" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                    Register
                </button>
            </div>
        </form>

    </div>
@endsection

