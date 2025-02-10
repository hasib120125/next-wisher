import { each, filter, orderBy, isEmpty, map } from "lodash";

export default {
    install(app, options) {
        let { categories } = options
        
        let properties = app.config.globalProperties;
        properties.getCategories = () => {
            return categories
        }
        properties.parentCategories = {
            ...categories.filter(item => {
                return !item.parent_id
            })
        }

        properties.getCategoryChild = (id) => {
            let subCat = {...filter(categories, item => item.parent_id == id)};
            return orderBy(subCat, ['name'], ['asc'])
        }
    }
}