import { registerBlockType } from "@wordpress/blocks";
import { useBlockProps } from "@wordpress/block-editor";
import { useSelect } from "@wordpress/data";
import "./style.css";

const EditComponent = (props) => {
  const { attributes } = props;
  const { quantidade, modo, postsSelecionados } = attributes;

  // Aplica classe personalizada sem sobrescrever o className do editor
  const blockProps = useBlockProps({ className: "phb-grid" });

  const posts = useSelect(
    (select) => {
      const query = {
        per_page: quantidade,
        order: "desc",
        orderby: "date",
      };

      if (modo === "manual" && postsSelecionados.length > 0) {
        query.include = postsSelecionados;
        query.orderby = "include";
      }

      return select("core").getEntityRecords("postType", "post", query);
    },
    [quantidade, modo, postsSelecionados]
  );

  if (!posts) {
    return <p {...blockProps}>Carregando posts…</p>;
  }

  if (posts.length === 0) {
    return <p {...blockProps}>Nenhum post encontrado.</p>;
  }

  const featured = posts[0];
  const smallPosts = posts.slice(1, 3);

  return (
    <div {...blockProps}>
      {/* Post grande */}
      <div className="phb-post phb-post--grande">
        <div className="phb-thumb-wrapper">
          <img
            className="phb-thumb-img"
            src={
              featured.featured_media_url ||
              "https://via.placeholder.com/600x400"
            }
            alt={featured.title.rendered}
          />
          <div className="phb-overlay">
            <span className="phb-categoria">
              {featured.category_names?.[0] || "Sem categoria"}
            </span>
            <h2 className="phb-titulo">{featured.title.rendered}</h2>
            <p>{featured.excerpt.rendered.replace(/<[^>]+>/g, "")}</p>
            <time>{featured.date}</time>
          </div>
        </div>
      </div>

      {/* Posts pequenos */}
      <div className="phb-grid-pequenos">
        {smallPosts.map((post) => (
          <div className="phb-post phb-post--pequeno" key={post.id}>
            <div className="phb-thumb-wrapper">
              <img
                className="phb-thumb-img"
                src={
                  post.featured_media_url ||
                  "https://via.placeholder.com/300x200"
                }
                alt={post.title.rendered}
              />
              <div className="phb-overlay">
                <span className="phb-categoria">
                  {post.category_names?.[0] || "Sem categoria"}
                </span>
                <h3 className="phb-titulo">{post.title.rendered}</h3>
                <time>{post.date}</time>
              </div>
            </div>
          </div>
        ))}
      </div>
    </div>
  );
};

registerBlockType("custom/post-highlight", {
  edit: EditComponent,
  save: () => null, // Bloco dinâmico: renderizado via PHP
});
