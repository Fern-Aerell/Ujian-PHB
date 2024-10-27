<script setup lang="ts">
import InputLabel from '@/Components/InputLabel.vue';
import Button from '@/Components/Buttons/Button.vue';
import RichTextEditor from '@/RichTextEditor/RichTextEditor.vue';
import { EAnswerType } from '@/types/index.d';
import { ref } from 'vue';
import Swal from 'sweetalert2';

interface Answer {
    content: string;
    correct: boolean|string;
}

const props = defineProps<{
    preview?: boolean
}>();

const answerType = ref(EAnswerType.objektif);
const answers = ref<Answer[]>([]);

function addAnswer() {
    answers.value.push(
        {
            content: '',
            correct: false
        }
    );
}

function answerObjektifChange(event: Event) {
    const id = parseInt((event.target as HTMLInputElement).id);
    answers.value.forEach((answer, index) => {
        if(index == id) {
            answer.correct = true;
        }else{
            answer.correct = false;
        }
    });
}

function answerTypeChange(event: Event) {
    const element = (event.target as HTMLInputElement);
    const type = element.value as EAnswerType;
    const clear = () => {
        answers.value = [];
    };
    const allowedChangeAnswerType = () => {
        answerType.value = type;
    };
    const notAllowedChangeAnswerType = () => {
        element.value = answerType.value;
    };
    const showWarning = () => {
        Swal.fire({
            title: "Apakah kamu yakin?",
            text: `Jika kamu merubah tipe jawaban dari ${answerType.value} ke ${type}, semua jawaban yang kamu buat akan hilang.`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya"
        }).then((result) => {
            if (result.isConfirmed) {
                clear();
                allowedChangeAnswerType();
            }else{
                notAllowedChangeAnswerType();
            }
        });
    };
    if(answers.value.length == 0) {
        allowedChangeAnswerType();
        return;
    }
    if((answerType.value == EAnswerType.objektif || answerType.value == EAnswerType.objektifKompleks) && type == EAnswerType.isian && answers.value.filter((answer) => answer.content.length > 0).length > 0) {
        showWarning();
        return;
    }
    allowedChangeAnswerType();
}

function deleteAnswer(index: number) {
    const allowedDeleteAnswer = () => {
        answers.value.splice(index, 1);
    };
    if(answers.value[index].content.length == 0) {
        allowedDeleteAnswer();
        return;
    }
    Swal.fire({
        title: "Apakah kamu yakin?",
        text: `Ingin menghapus jawaban ke-${index+1} ini`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Ya"
    }).then((result) => {
        if (result.isConfirmed) {
            allowedDeleteAnswer();
        }
    });
}

</script>

<template>
    <div class="flex flex-col">
        <InputLabel v-if="!props.preview" value="Tipe Jawaban" class="required" />
        <div v-if="!props.preview" class="flex flex-row flex-wrap gap-2 items-center mt-2">
            <select @change="answerTypeChange" :value="answerType" name="answer_type" id="answer_type" class="w-fit">
                <option :value="EAnswerType.objektif" selected>Objektif</option>
                <option :value="EAnswerType.objektifKompleks">Objektif kompleks</option>
                <option :value="EAnswerType.isian">Isian</option>
            </select>
            <Button @click="addAnswer" text="Tambah jawaban" bg-color="primary" text-color="white" class="!w-fit h-fit px-5" />
        </div>
        <div class="flex flex-col gap-5 mt-3">
            <textarea v-if="props.preview && answerType == EAnswerType.isian" rows="1" :placeholder="`Masukkan jawaban... ${answers.map((answer) => answer.content).join(', ')}`"></textarea>
            <div v-for="(answer, index) in answers" class="flex gap-3" :class="{'flex-col': !preview}">
                <div class="flex flex-row gap-2" :class="{'items-start translate-y-[4.5px]': preview, 'items-center': !preview}">
                    <input @change="answerObjektifChange" v-if="answerType == EAnswerType.objektif" type="radio" name="answer" :id="`${index}`">
                    <input v-model="answer.correct" v-if="answerType == EAnswerType.objektifKompleks" type="checkbox" name="answer" :id="`${index}`">
                    <h1 v-if="!preview">Jawaban Ke-{{ index + 1 }}</h1>
                </div>
                <textarea v-if="!props.preview && answerType == EAnswerType.isian" v-model="answer.content" name="answer" rows="1"></textarea>
                <RichTextEditor v-if="!props.preview && (answerType == EAnswerType.objektif || answerType == EAnswerType.objektifKompleks)" v-model="answer.content"/>
                <p v-if="props.preview && answerType != EAnswerType.isian" class="w-full" v-html="answer.content"></p>
                <Button v-if="!props.preview" @click="deleteAnswer(index)" text="Hapus" bg-color="danger" text-color="white" class="!w-fit px-5" />
            </div>
        </div>
    </div>
</template>