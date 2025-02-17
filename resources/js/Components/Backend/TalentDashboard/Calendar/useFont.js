import { ref } from "vue"

export default function useFont() {
    const fonts = ref([
        {
            fontFamily: "'ABeeZee', sans-serif",
            title: "ABeeZee",
            fontWeight: 400,
            link: 'https://fonts.googleapis.com/css2?family=ABeeZee:ital@0;1&display=swap',
            isSelected: false,
            isItalic: false
        },
        {
            fontFamily: "'Abril Fatface'",
            title: "Abril Fatface",
            fontWeight: 400,
            link: 'https://fonts.googleapis.com/css2?family=Abril+Fatface&display=swap',
            isSelected: false,
            isItalic: false
        },
        {
            fontFamily: "'Advent Pro', sans-serif",
            title: "Advent Pro",
            fontWeight: 700,
            link: 'https://fonts.googleapis.com/css2?family=Advent+Pro:wght@400;700&display=swap',
            isSelected: false,
            isItalic: false
        },
        {
            fontFamily: "'Advent Pro', sans-serif",
            title: "Advent Pro Thin",
            fontWeight: 400,
            link: 'https://fonts.googleapis.com/css2?family=Advent+Pro:wght@400;700&display=swap',
            isSelected: false,
            isItalic: false
        },
        {
            fontFamily: "'Alef', sans-serif",
            title: "Alef",
            fontWeight: 700,
            link: 'https://fonts.googleapis.com/css2?family=Alef:wght@400;700&display=swap',
            isSelected: false,
            isItalic: false
        },
        {
            fontFamily: "'Alef', sans-serif",
            title: "Alef Thin",
            fontWeight: 400,
            link: 'https://fonts.googleapis.com/css2?family=Alef:wght@400;700&display=swap',
            isSelected: false,
            isItalic: false
        },
        {
            fontFamily: "'Amaranth', sans-serif",
            title: "Amaranth",
            fontWeight: 700,
            link: 'https://fonts.googleapis.com/css2?family=Amaranth:wght@400;700&display=swap',
            isSelected: false,
            isItalic: false
        },
        {
            fontFamily: "'Amaranth', sans-serif",
            title: "Amaranth Thin",
            fontWeight: 400,
            link: 'https://fonts.googleapis.com/css2?family=Amaranth:wght@400;700&display=swap',
            isSelected: false,
            isItalic: false
        },
        {
            fontFamily: "'Gochi Hand', cursive",
            title: "Gochi Hand",
            fontWeight: 400,
            link: 'https://fonts.googleapis.com/css2?family=Gochi+Hand&display=swap',
            isSelected: false,
            isItalic: false
        },
        {
            fontFamily: "'Just Another Hand', cursive",
            title: "Just Another Hand",
            fontWeight: 400,
            link: 'https://fonts.googleapis.com/css2?family=Just+Another+Hand&display=swap',
            isSelected: false,
            isItalic: false
        },
        {
            fontFamily: "'Alegreya', serif",
            title: "Alegreya Bold",
            fontWeight: 800,
            link: 'https://fonts.googleapis.com/css2?family=Alegreya:wght@400;800&display=swap',
            isSelected: false,
            isItalic: false
        },
        {
            fontFamily: "'Alegreya', serif",
            title: "Alegreya Italic",
            fontWeight: 400,
            link: 'https://fonts.googleapis.com/css2?family=Alegreya:ital,wght@1,400;1,800&display=swap',
            isSelected: false,
            isItalic: false
        },
        {
            fontFamily: "'Alegreya Sans', sans-serif",
            title: "Alegreya",
            fontWeight: 400,
            link: 'https://fonts.googleapis.com/css2?family=Alegreya+Sans:wght@400;700&display=swap',
            isSelected: false,
            isItalic: false
        },
        {
            fontFamily: "'Alegreya Sans SC', sans-serif",
            title: "Alegreya Sans SC",
            fontWeight: 400,
            link: 'https://fonts.googleapis.com/css2?family=Alegreya+Sans+SC:wght@400;700&display=swap',
            isSelected: false,
            isItalic: false
        },
        {
            fontFamily: "'Alegreya Sans SC', sans-serif",
            title: "Alegreya Sans SC",
            fontWeight: 700,
            link: 'https://fonts.googleapis.com/css2?family=Alegreya+Sans+SC:wght@400;700&display=swap',
            isSelected: false,
            isItalic: false
        },
        {
            fontFamily: "'Alfa Slab One', cursive",
            title: "Alfa Slab One",
            fontWeight: 400,
            link: 'https://fonts.googleapis.com/css2?family=Alfa+Slab+One&display=swap',
            isSelected: false,
            isItalic: false
        },
        {
            fontFamily: "'Allerta Stencil', sans-serif",
            title: "Allerta Stencil",
            fontWeight: 400,
            link: 'https://fonts.googleapis.com/css2?family=Allerta+Stencil&display=swap',
            isSelected: false,
            isItalic: false
        },
        {
            fontFamily: "'Beau Rivage', cursive",
            title: "Beau Rivage",
            fontWeight: 400,
            link: 'https://fonts.googleapis.com/css2?family=Beau+Rivage&display=swap',
            isSelected: false,
            isItalic: false
        },
        {
            fontFamily: "'Allura', cursive",
            title: "Allura",
            fontWeight: 400,
            link: 'https://fonts.googleapis.com/css2?family=Allura&display=swap',
            isSelected: false,
            isItalic: false
        },
        {
            fontFamily: "'Amaranth', sans-serif",
            title: "Amaranth Bold",
            fontWeight: 700,
            link: 'https://fonts.googleapis.com/css2?family=Amaranth:wght@400;700&display=swap',
            isSelected: false,
            isItalic: false
        },
        {
            fontFamily: "'Amarante', cursive",
            title: "Amaranth",
            fontWeight: 400,
            link: 'https://fonts.googleapis.com/css2?family=Amarante&display=swap',
            isSelected: false,
            isItalic: false
        },
        {
            fontFamily: "'Amatic SC', cursive",
            title: "Amatic SC",
            fontWeight: 400,
            link: 'https://fonts.googleapis.com/css2?family=Amatic+SC:wght@400;700&display=swap',
            isSelected: false,
            isItalic: false
        },
        {
            fontFamily: "'Amiko', sans-serif",
            title: "Amiko Bold",
            fontWeight: 700,
            link: 'https://fonts.googleapis.com/css2?family=Amiko:wght@400;700&display=swap',
            isSelected: false,
            isItalic: false
        },
        {
            fontFamily: "'Amiko', sans-serif",
            title: "Amiko",
            fontWeight: 400,
            link: 'https://fonts.googleapis.com/css2?family=Amiko:wght@400;700&display=swap'    ,
            isSelected: false,
            isItalic: false
        },
        {
            fontFamily: "'Andika', sans-serif",
            title: "Andika",
            fontWeight: 400,
            link: 'https://fonts.googleapis.com/css2?family=Andika:wght@400;700&display=swap',
            isSelected: false,
            isItalic: false
        },
        {
            fontFamily: "'Anonymous Pro', monospace",
            title: "Anonymous Pro Bold",
            fontWeight: 700,
            link: 'https://fonts.googleapis.com/css2?family=Anonymous+Pro:wght@400;700&display=swap',
            isSelected: false,
            isItalic: false
        },
        {
            fontFamily: "'Anonymous Pro', monospace",
            title: "Anonymous Pro ",
            fontWeight: 400,
            link: 'https://fonts.googleapis.com/css2?family=Anonymous+Pro:wght@400;700&display=swap',
            isSelected: false,
            isItalic: false
        },
        {
            fontFamily: "'Antic', sans-serif",
            title: "Antic",
            fontWeight: 400,
            link: 'https://fonts.googleapis.com/css2?family=Antic&display=swap',
            isSelected: false,
            isItalic: false
        },
        {
            fontFamily: "'Antic Didone, serif",
            title: "Antic Didone ",
            fontWeight: 400,
            link: 'https://fonts.googleapis.com/css2?family=Antic+Didone&display=swap',
            isSelected: false,
            isItalic: false
        },





        {
            fontFamily: "'Anton', sans-serif",
            title: "Anton ",
            fontWeight: 400,
            link: 'https://fonts.googleapis.com/css2?family=Anton&display=swap',
            isSelected: false,
            isItalic: false
        },
        {
            fontFamily: "'Arapey', serif",
            title: "Arapey ",
            fontWeight: 400,
            link: 'https://fonts.googleapis.com/css2?family=Arapey&display=swap',
            isSelected: false,
            isItalic: false
        },
        {
            fontFamily: "'Arbutus Slab', serif",
            title: "Arbutus Slab ",
            fontWeight: 400,
            link: 'https://fonts.googleapis.com/css2?family=Arapey&display=swap',
            isSelected: false,
            isItalic: false
        },
        {
            fontFamily: "'Architects Daughter', cursive",
            title: "Architects Daughter",
            fontWeight: 400,
            link: 'https://fonts.googleapis.com/css2?family=Architects+Daughter&display=swap',
            isSelected: false,
            isItalic: false
        },
        {
            fontFamily: "'Archivo Black', sans-serif",
            title: "Archivo Black",
            fontWeight: 400,
            link: 'https://fonts.googleapis.com/css2?family=Archivo+Black&display=swap',
            isSelected: false,
            isItalic: false
        },
        {
            fontFamily: "'Arima Madurai', cursive",
            title: "Arima Madurai Bold",
            fontWeight: 700,
            link: 'https://fonts.googleapis.com/css2?family=Arima+Madurai:wght@400;700&display=swap',
            isSelected: false,
            isItalic: false
        },
        {
            fontFamily: "'Arima Madurai', cursive",
            title: "Arima Madurai",
            fontWeight: 400,
            link: 'https://fonts.googleapis.com/css2?family=Arima+Madurai:wght@400;700&display=swap',
            isSelected: false,
            isItalic: false
        },
        {
            fontFamily: "'Arimo', sans-serif",
            title: "Arimo Bold",
            fontWeight: 700,
            link: 'https://fonts.googleapis.com/css2?family=Arimo:wght@400;700&display=swap',
            isSelected: false,
            isItalic: false
        },
        {
            fontFamily: "'Arvo', serif",
            title: "Arvo Bold",
            fontWeight: 700,
            link: 'https://fonts.googleapis.com/css2?family=Arvo:wght@400;700&display=swap',
            isSelected: false,
            isItalic: false
        },
        {
            fontFamily: "'Arvo', serif",
            title: "Arvo",
            fontWeight: 400,
            link: 'https://fonts.googleapis.com/css2?family=Arvo:wght@400;700&display=swap',
            isSelected: false,
            isItalic: false
        },
        {
            fontFamily: "'Asap', sans-serif",
            title: "Asap",
            fontWeight: 400,
            link: 'https://fonts.googleapis.com/css2?family=Asap&display=swap',
            isSelected: false,
            isItalic: false
        },
        {
            fontFamily: "'Asset', cursive",
            title: "Asset",
            fontWeight: 400,
            link: 'https://fonts.googleapis.com/css2?family=Asset&display=swap',
            isSelected: false,
            isItalic: false
        },
        {
            fontFamily: "'Assistant', sans-serif",
            title: "Assistant Bold",
            fontWeight: 700,
            link: 'https://fonts.googleapis.com/css2?family=Assistant:wght@300;400;700&display=swap',
            isSelected: false,
            isItalic: false
        },
        {
            fontFamily: "'Assistant', sans-serif",
            title: "Assistant Light",
            fontWeight: 300,
            link: 'https://fonts.googleapis.com/css2?family=Assistant:wght@300;400;700&display=swap',
            isSelected: false,
            isItalic: false
        },
        {
            fontFamily: "'Assistant', sans-serif",
            title: "Assistant",
            fontWeight: 400,
            link: 'https://fonts.googleapis.com/css2?family=Assistant:wght@300;400;700&display=swap',
            isSelected: false,
            isItalic: false
        },
        {
            fontFamily: "'Athiti', sans-serif",
            title: "Athiti Bold",
            fontWeight: 700,
            link: 'https://fonts.googleapis.com/css2?family=Athiti:wght@300;400;700&display=swap',
            isSelected: false,
            isItalic: false
        },
        {
            fontFamily: "'Athiti', sans-serif",
            title: "Athiti Light",
            fontWeight: 300,
            link: 'https://fonts.googleapis.com/css2?family=Athiti:wght@300;400;700&display=swap',
            isSelected: false,
            isItalic: false
        },
        {
            fontFamily: "'Athiti', sans-serif",
            title: "Athiti Light",
            fontWeight: 400,
            link: 'https://fonts.googleapis.com/css2?family=Athiti:wght@300;400;700&display=swap',
            isSelected: false,
            isItalic: false
        },      
        {
            fontFamily: "'Atma', cursive",
            title: "Atma Bold",
            fontWeight: 700,
            link: 'https://fonts.googleapis.com/css2?family=Atma:wght@300;400;700&display=swap',
            isSelected: false,
            isItalic: false
        },
        {
            fontFamily: "'Atma', cursive",
            title: "Atma Light",
            fontWeight: 300,
            link: 'https://fonts.googleapis.com/css2?family=Atma:wght@300;400;700&display=swap',
            isSelected: false,
            isItalic: false
        },     
        {
            fontFamily: "'Bad Script', cursive",
            title: "Bad Script",
            fontWeight: 400,
            link: 'https://fonts.googleapis.com/css2?family=Bad+Script&display=swap',
            isSelected: false,
            isItalic: false
        },
        {
            fontFamily: "'Bangers', cursive",
            title: "Bangers",
            fontWeight: 400,
            link: 'https://fonts.googleapis.com/css2?family=Bangers&display=swap',
            isSelected: false,
            isItalic: false
        },
       
        {
            fontFamily: "'Bebas Neue', cursive",
            title: "Bebas Neue",
            fontWeight: 400,
            link: 'https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap',
            isSelected: false,
            isItalic: false
        },
        {
            fontFamily: "'Belgrano', serif",
            title: "Belgrano",
            fontWeight: 400,
            link: 'https://fonts.googleapis.com/css2?family=Belgrano&display=swap',
            isSelected: false,
            isItalic: false
        },
        {
            fontFamily: "'Belleza', sans-serif",
            title: "Belleza",
            fontWeight: 400,
            link: 'https://fonts.googleapis.com/css2?family=Belleza&display=swap',
            isSelected: false,
            isItalic: false
        },
        {
            fontFamily: "'Bentham', serif",
            title: "Bentham",
            fontWeight: 400,
            link: 'https://fonts.googleapis.com/css2?family=Bentham&display=swap',
            isSelected: false,
            isItalic: false
        },
        {
            fontFamily: "'Berkshire Swash', cursive",
            title: "Berkshire Swash",
            fontWeight: 400,
            link: 'https://fonts.googleapis.com/css2?family=Berkshire+Swash&display=swap',
            isSelected: false,
            isItalic: false
        },
        {
            fontFamily: "'Beau Rivage', cursive",
            title: "Beau Rivage",
            fontWeight: 400,
            link: 'https://fonts.googleapis.com/css2?family=Beau+Rivage&display=swap',
            isSelected: false,
            isItalic: false
        },
        {
            fontFamily: "'Bevan', cursive",
            title: "Bevan ",
            fontWeight: 400,
            link: 'https://fonts.googleapis.com/css2?family=Bevan&display=swap',
            isSelected: false,
            isItalic: false
        },
        {
            fontFamily: "'Bitter', serif",
            title: "Bitter Bold ",
            fontWeight: 700,
            link: 'https://fonts.googleapis.com/css2?family=Bitter:wght@300;400;700&display=swap',
            isSelected: false,
            isItalic: false
        },
        {
            fontFamily: "'Bitter', serif",
            title: "Bitter Light ",
            fontWeight: 300,
            link: 'https://fonts.googleapis.com/css2?family=Bitter:wght@300;400;700&display=swap',
            isSelected: false,
            isItalic: false
        },
        {
            fontFamily: "'Bitter', serif",
            title: "Bitter",
            fontWeight: 400,
            link: 'https://fonts.googleapis.com/css2?family=Bitter:wght@300;400;700&display=swap',
            isSelected: false,
            isItalic: false
        },
        {
            fontFamily: "'Black Ops One', cursive",
            title: "Black Ops One",
            fontWeight: 400,
            link: 'https://fonts.googleapis.com/css2?family=Black+Ops+One&display=swap',
            isSelected: false,
            isItalic: false
        },
    ])

    return {
        fonts
    }
}