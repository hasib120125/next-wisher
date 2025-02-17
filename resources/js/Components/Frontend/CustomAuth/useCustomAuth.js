import SignIn from '@/Components/Frontend/CustomAuth/SignIn.vue'
import SignUp from '@/Components/Frontend/CustomAuth/SignUp.vue'
import SignOption from '@/Components/Frontend/CustomAuth/SignOption.vue'
import TalentSignup from '@/Components/Frontend/CustomAuth/TalentSignup.vue'
import ForgotPassword from '@/Components/Frontend/CustomAuth/ForgotPassword.vue'
import { ref } from 'vue'

export const componentList = ref({
    SignIn: {
        title: 'Sign in',
        component: SignIn
    },
    SignOption: {
        title: '',
        component: SignOption
    },
    SignUp: {
        title: 'Create Your Account',
        component: SignUp
    },
    TalentSignup: {
        title: 'Create Your Account as Talent',
        component: TalentSignup
    },
    ForgotPassword: {
        title: 'Forgot password?',
        component: ForgotPassword
    }
})

export const templateName = ref('SignIn')
