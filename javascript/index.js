import { createElement, useState } from "@wordpress/element";
import { SelectControl } from "@wordpress/components";
import { useSelect, useDispatch } from "@wordpress/data";
import { store as coreStore } from "@wordpress/core-data";
import { store as editorStore } from "@wordpress/editor";
import { defaultHooks } from "@wordpress/hooks";
import { get } from "lodash";

const DEFAULT_QUERY = {
  per_page: -1,
  orderby: "name",
  order: "asc",
  _fields: "id,name",
  context: "view",
};

function languageSelector({ slug }) {
  const { terms, availableTerms, taxonomy, hasAssignAction } = useSelect(
    (select) => {
      const { getCurrentPost, getEditedPostAttribute } = select(editorStore);
      const { getTaxonomy, getEntityRecords, isResolving } = select(coreStore);
      const _taxonomy = getTaxonomy(slug);
      const post = getCurrentPost();

      return {
        terms: _taxonomy ? getEditedPostAttribute(_taxonomy.rest_base) : [],
        availableTerms: getEntityRecords("taxonomy", slug, DEFAULT_QUERY) || [],
        taxonomy: _taxonomy,
        hasAssignAction: _taxonomy
          ? get(
              post,
              ["_links", "wp:action-assign-" + _taxonomy.rest_base],
              false
            )
          : false,
      };
    }
  );

  if (!hasAssignAction) {
    return null;
  }

  const { editPost } = useDispatch(editorStore);
  //const { saveEntityRecord } = useDispatch(coreStore);

  // update term for post
  const onUpdateTerm = (termId) => {
    console.log("editPost");
    editPost({ [taxonomy.rest_base]: termId ? [termId] : [] });
  };

  // handle for select term
  const onChange = (termId) => {
    console.log(termId);
    termId = termId === "default" ? false : termId;
    onUpdateTerm(termId);
  };

  const [termId, setTermId] = useState(terms[0] || "default");

  return createElement(SelectControl, {
    label: "Language",
    value: termId,
    options: [
      ...availableTerms.map((term) => ({ label: term.name, value: term.id })),
      { label: "Default", value: "default" },
    ],
    onChange: (termId) => {
      setTermId(termId);
      onChange(termId);
    },
    __nextHasNoMarginBottom: true,
    help: "Select language for post."
  });
}

function customLanguageSelector(OriginalComponent) {
  return function (props) {
    if (props.slug === "cryz_lang") {
      return createElement(languageSelector, props);
    } else {
      return createElement(OriginalComponent, props);
    }
  };
}

defaultHooks.addFilter(
  "editor.PostTaxonomyType",
  "cryz-lang/custom-language-selector",
  customLanguageSelector
);
